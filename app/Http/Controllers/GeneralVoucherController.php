<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneralVoucherRequest;
use App\Models\Bank;
use App\Models\BankLedger;
use App\Models\Business;
use App\Models\GeneralVoucher;
use App\Models\GeneralVoucherAttachment;
use App\Models\Party;
use App\Models\PartyLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GeneralVoucherController extends Controller
{
    public function index(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $query = GeneralVoucher::with(['bank.currency', 'party', 'user'])
            ->forBusiness($businessId);

        if ($request->filled('general_voucher_id')) {
            $query->where('general_voucher_id', $request->general_voucher_id);
        }
        if ($request->filled('bank_id')) {
            $query->where('bank_id', $request->bank_id);
        }
        if ($request->filled('party_id')) {
            $query->where('party_id', $request->party_id);
        }
        if ($request->filled('date_from')) {
            $query->where('date_added', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date_added', '<=', $request->date_to);
        }

        $vouchers = $query->orderByDesc('general_voucher_id')->paginate(15)->withQueryString();

        $banks = Bank::forBusiness($businessId)->active()->orderBy('bank_name')->get(['bank_id', 'bank_name']);
        $parties = Party::forBusiness($businessId)->active()->orderBy('party_name')->get(['party_id', 'party_name']);

        return view('general-vouchers.index', compact('vouchers', 'banks', 'parties'));
    }

    public function create()
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $banks = Bank::forBusiness($businessId)
            ->active()
            ->with('currency')
            ->orderBy('bank_name')
            ->get();
        $parties = Party::forBusiness($businessId)
            ->active()
            ->orderBy('party_name')
            ->get();

        return view('general-vouchers.create', compact('banks', 'parties'));
    }

    public function store(GeneralVoucherRequest $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $bank = Bank::forBusiness($businessId)->active()->findOrFail($request->bank_id);
        $party = Party::forBusiness($businessId)->active()->findOrFail($request->party_id);

        if ($request->entry_type == GeneralVoucher::ENTRY_TYPE_DEBIT) {
            $balance = (float) BankLedger::where('bank_id', $bank->bank_id)
                ->selectRaw('COALESCE(SUM(deposit_amount), 0) - COALESCE(SUM(withdrawal_amount), 0) as balance')
                ->value('balance');
            if ((float) $request->amount > $balance) {
                return back()->withInput()->with('error', 'Insufficient Balance in Bank Account.');
            }
        }

        DB::beginTransaction();
        try {
            $voucher = GeneralVoucher::create([
                'business_id' => $businessId,
                'date_added' => $request->date_added,
                'bank_id' => $bank->bank_id,
                'party_id' => $party->party_id,
                'entry_type' => (int) $request->entry_type,
                'amount' => $request->amount,
                'rate' => $request->rate,
                'details' => $request->details,
                'user_id' => auth()->id(),
            ]);

            $this->syncLedgersForVoucher($voucher, $bank, $party);

            if ($request->hasFile('attachments')) {
                $titles = $request->input('attachment_titles', []);
                foreach ($request->file('attachments') as $index => $file) {
                    $path = $file->store('general_vouchers', 'public');
                    GeneralVoucherAttachment::create([
                        'general_voucher_id' => $voucher->general_voucher_id,
                        'file_title' => $titles[$index] ?? null,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('general-vouchers.index')
                ->with('success', 'General Voucher has been added successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create general voucher: ' . $e->getMessage());
        }
    }

    public function show(GeneralVoucher $generalVoucher)
    {
        $businessId = session('active_business');
        if (!$businessId || $generalVoucher->business_id != $businessId) {
            abort(403, 'Unauthorized access to this general voucher.');
        }

        $generalVoucher->load(['bank.currency', 'party', 'user', 'attachments']);

        return view('general-vouchers.show', compact('generalVoucher'));
    }

    public function edit(GeneralVoucher $generalVoucher)
    {
        $businessId = session('active_business');
        if (!$businessId || $generalVoucher->business_id != $businessId) {
            abort(403, 'Unauthorized access to this general voucher.');
        }

        $banks = Bank::forBusiness($businessId)->active()->with('currency')->orderBy('bank_name')->get();
        $parties = Party::forBusiness($businessId)->active()->orderBy('party_name')->get();
        $generalVoucher->load('attachments');

        return view('general-vouchers.edit', compact('generalVoucher', 'banks', 'parties'));
    }

    public function update(GeneralVoucherRequest $request, GeneralVoucher $generalVoucher)
    {
        $businessId = session('active_business');
        if (!$businessId || $generalVoucher->business_id != $businessId) {
            abort(403, 'Unauthorized access to this general voucher.');
        }

        $bank = Bank::forBusiness($businessId)->active()->findOrFail($request->bank_id);
        $party = Party::forBusiness($businessId)->active()->findOrFail($request->party_id);

        if ($request->entry_type == GeneralVoucher::ENTRY_TYPE_DEBIT) {
            $balance = (float) BankLedger::where('bank_id', $bank->bank_id)
                ->selectRaw('COALESCE(SUM(deposit_amount), 0) - COALESCE(SUM(withdrawal_amount), 0) as balance')
                ->value('balance');
            $oldDebit = ($generalVoucher->entry_type == GeneralVoucher::ENTRY_TYPE_DEBIT && (int) $generalVoucher->bank_id === (int) $bank->bank_id)
                ? (float) $generalVoucher->amount : 0;
            if ((float) $request->amount > $balance + $oldDebit) {
                return back()->withInput()->with('error', 'Insufficient Balance in Bank Account.');
            }
        }

        DB::beginTransaction();
        try {
            $generalVoucher->update([
                'date_added' => $request->date_added,
                'bank_id' => $bank->bank_id,
                'party_id' => $party->party_id,
                'entry_type' => (int) $request->entry_type,
                'amount' => $request->amount,
                'rate' => $request->rate,
                'details' => $request->details,
            ]);

            BankLedger::where('voucher_id', $generalVoucher->general_voucher_id)
                ->where('voucher_type', GeneralVoucher::VOUCHER_TYPE)
                ->delete();
            PartyLedger::where('voucher_id', $generalVoucher->general_voucher_id)
                ->where('voucher_type', GeneralVoucher::VOUCHER_TYPE)
                ->delete();

            $this->syncLedgersForVoucher($generalVoucher->fresh(), $bank, $party);

            if ($request->hasFile('attachments')) {
                $titles = $request->input('attachment_titles', []);
                foreach ($request->file('attachments') as $index => $file) {
                    $path = $file->store('general_vouchers', 'public');
                    GeneralVoucherAttachment::create([
                        'general_voucher_id' => $generalVoucher->general_voucher_id,
                        'file_title' => $titles[$index] ?? null,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('general-vouchers.index')
                ->with('success', 'General Voucher has been updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update general voucher: ' . $e->getMessage());
        }
    }

    public function destroy(GeneralVoucher $generalVoucher)
    {
        $businessId = session('active_business');
        if (!$businessId || $generalVoucher->business_id != $businessId) {
            abort(403, 'Unauthorized access to this general voucher.');
        }

        DB::beginTransaction();
        try {
            BankLedger::where('voucher_id', $generalVoucher->general_voucher_id)
                ->where('voucher_type', GeneralVoucher::VOUCHER_TYPE)
                ->delete();
            PartyLedger::where('voucher_id', $generalVoucher->general_voucher_id)
                ->where('voucher_type', GeneralVoucher::VOUCHER_TYPE)
                ->delete();

            foreach ($generalVoucher->attachments as $att) {
                Storage::disk('public')->delete($att->file_path);
            }
            $generalVoucher->delete();

            DB::commit();
            return redirect()->route('general-vouchers.index')
                ->with('success', 'General Voucher has been deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete general voucher: ' . $e->getMessage());
        }
    }

    public function deleteAttachment(GeneralVoucherAttachment $attachment)
    {
        $voucher = $attachment->generalVoucher;

        $businessId = session('active_business');
        if (!$businessId || $voucher->business_id != $businessId) {
            abort(403, 'Unauthorized access.');
        }

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return response()->json(['success' => true, 'message' => 'Attachment deleted successfully.']);
    }

    public function print(GeneralVoucher $generalVoucher)
    {
        $businessId = session('active_business');
        if (!$businessId || $generalVoucher->business_id != $businessId) {
            abort(403, 'Unauthorized access to this general voucher.');
        }

        $business = Business::find($businessId);
        $generalVoucher->load(['bank.currency', 'party', 'user', 'attachments']);

        return view('general-vouchers.print', compact('generalVoucher', 'business'));
    }

    protected function syncLedgersForVoucher(GeneralVoucher $voucher, Bank $bank, Party $party): void
    {
        $details = trim((string) $voucher->details);
        $entryType = (int) $voucher->entry_type;
        $amount = (float) $voucher->amount;
        $rate = (float) $voucher->rate;
        $dateAdded = $voucher->date_added;
        $voucherType = GeneralVoucher::VOUCHER_TYPE;
        $voucherId = $voucher->general_voucher_id;

        $partyDetails = 'Bank: ' . $bank->bank_name . ($details ? ', ' . $details : '');
        PartyLedger::create([
            'party_id' => $party->party_id,
            'currency_id' => $bank->currency_id,
            'voucher_id' => $voucherId,
            'voucher_type' => $voucherType,
            'date_added' => $dateAdded,
            'transaction_party' => $bank->bank_name,
            'rate' => $rate,
            'details' => $partyDetails,
            'user_id' => auth()->id(),
            'credit_amount' => $entryType === GeneralVoucher::ENTRY_TYPE_CREDIT ? $amount : 0,
            'debit_amount' => $entryType === GeneralVoucher::ENTRY_TYPE_DEBIT ? $amount : 0,
        ]);

        $bankDetails = 'Party: ' . $party->party_name . ', Rate ' . $rate . ($details ? ', ' . $details : '');
        BankLedger::create([
            'bank_id' => $bank->bank_id,
            'voucher_id' => $voucherId,
            'voucher_type' => $voucherType,
            'date_added' => $dateAdded,
            'details' => $bankDetails,
            'user_id' => auth()->id(),
            'deposit_amount' => $entryType === GeneralVoucher::ENTRY_TYPE_CREDIT ? $amount : 0,
            'withdrawal_amount' => $entryType === GeneralVoucher::ENTRY_TYPE_DEBIT ? $amount : 0,
        ]);
    }
}

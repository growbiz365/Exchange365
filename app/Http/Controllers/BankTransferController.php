<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankTransferRequest;
use App\Models\Bank;
use App\Models\BankLedger;
use App\Models\BankTransfer;
use App\Models\BankTransferAttachment;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BankTransferController extends Controller
{
    public function index(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $query = BankTransfer::with([
            'fromBank.currency',
            'toBank.currency',
        ])->forBusiness($businessId);

        if ($request->filled('bank_transfer_id')) {
            $query->where('bank_transfer_id', $request->bank_transfer_id);
        }
        if ($request->filled('date_from')) {
            $query->where('date_added', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date_added', '<=', $request->date_to);
        }

        $transfers = $query->orderByDesc('bank_transfer_id')->paginate(15);

        return view('bank-transfers.index', compact('transfers'));
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
            ->orderBy('bank_type_id')
            ->orderBy('bank_name')
            ->get();

        return view('bank-transfers.create', compact('banks'));
    }

    public function store(BankTransferRequest $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        DB::beginTransaction();
        try {
            $fromBank = Bank::forBusiness($businessId)->active()->findOrFail($request->from_account_id);
            $toBank = Bank::forBusiness($businessId)->active()->findOrFail($request->to_account_id);

            if ($fromBank->currency_id !== $toBank->currency_id) {
                return back()->withInput()->with('error', 'Please select same currency accounts.');
            }

            $available = (float) BankLedger::where('bank_id', $fromBank->bank_id)
                ->selectRaw('COALESCE(SUM(deposit_amount), 0) - COALESCE(SUM(withdrawal_amount), 0) as balance')
                ->value('balance');

            if ((float) $request->amount > $available) {
                return back()->withInput()->with('error', 'Insufficient Balance in Bank Account.');
            }

            $transfer = BankTransfer::create([
                'business_id' => $businessId,
                'date_added' => $request->date_added,
                'from_account_id' => $fromBank->bank_id,
                'to_account_id' => $toBank->bank_id,
                'amount' => $request->amount,
                'details' => $request->details,
                'user_id' => auth()->id(),
            ]);

            $voucherType = 'Bank Transfer';
            $details = trim((string) $request->details);

            BankLedger::create([
                'bank_id' => $fromBank->bank_id,
                'withdrawal_amount' => $request->amount,
                'deposit_amount' => 0,
                'voucher_id' => $transfer->bank_transfer_id,
                'voucher_type' => $voucherType,
                'date_added' => $request->date_added,
                'details' => trim('To Account: ' . $toBank->bank_name . ($details ? (', ' . $details) : '')),
                'user_id' => auth()->id(),
            ]);

            BankLedger::create([
                'bank_id' => $toBank->bank_id,
                'deposit_amount' => $request->amount,
                'withdrawal_amount' => 0,
                'voucher_id' => $transfer->bank_transfer_id,
                'voucher_type' => $voucherType,
                'date_added' => $request->date_added,
                'details' => trim('From Account: ' . $fromBank->bank_name . ($details ? (', ' . $details) : '')),
                'user_id' => auth()->id(),
            ]);

            if ($request->hasFile('attachments')) {
                $attachmentTitles = $request->input('attachment_titles', []);

                foreach ($request->file('attachments') as $index => $file) {
                    $filePath = $file->store('bank_transfers', 'public');

                    BankTransferAttachment::create([
                        'bank_transfer_id' => $transfer->bank_transfer_id,
                        'file_title' => $attachmentTitles[$index] ?? null,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('bank-transfers.index')
                ->with('success', 'Bank Transfer has been added successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create bank transfer: ' . $e->getMessage());
        }
    }

    public function show(BankTransfer $bankTransfer)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }
        if ($bankTransfer->business_id != $businessId) {
            abort(403, 'Unauthorized access to this bank transfer.');
        }

        $bankTransfer->load([
            'fromBank.currency',
            'toBank.currency',
            'user',
            'attachments',
        ]);

        return view('bank-transfers.show', compact('bankTransfer'));
    }

    public function edit(BankTransfer $bankTransfer)
    {
        $businessId = session('active_business');
        if (!$businessId || $bankTransfer->business_id != $businessId) {
            abort(403, 'Unauthorized access to this bank transfer.');
        }

        $banks = Bank::forBusiness($businessId)
            ->active()
            ->with('currency')
            ->orderBy('bank_type_id')
            ->orderBy('bank_name')
            ->get();

        $bankTransfer->load('attachments');

        return view('bank-transfers.edit', compact('bankTransfer', 'banks'));
    }

    public function update(BankTransferRequest $request, BankTransfer $bankTransfer)
    {
        $businessId = session('active_business');
        if (!$businessId || $bankTransfer->business_id != $businessId) {
            abort(403, 'Unauthorized access to this bank transfer.');
        }

        DB::beginTransaction();
        try {
            $fromBank = Bank::forBusiness($businessId)->active()->findOrFail($request->from_account_id);
            $toBank = Bank::forBusiness($businessId)->active()->findOrFail($request->to_account_id);

            if ($fromBank->currency_id !== $toBank->currency_id) {
                return back()->withInput()->with('error', 'Please select same currency accounts.');
            }

            $available = (float) BankLedger::where('bank_id', $fromBank->bank_id)
                ->selectRaw('COALESCE(SUM(deposit_amount), 0) - COALESCE(SUM(withdrawal_amount), 0) as balance')
                ->value('balance');

            $oldAmount = (float) $bankTransfer->amount;
            $oldFromAccountId = (int) $bankTransfer->from_account_id;

            $canUseOldAmount = ($oldFromAccountId === (int) $fromBank->bank_id);
            $effectiveAvailable = $canUseOldAmount ? ($available + $oldAmount) : $available;

            if ((float) $request->amount > $effectiveAvailable) {
                return back()->withInput()->with('error', 'Insufficient Balance in Bank Account.');
            }

            $bankTransfer->update([
                'date_added' => $request->date_added,
                'from_account_id' => $fromBank->bank_id,
                'to_account_id' => $toBank->bank_id,
                'amount' => $request->amount,
                'details' => $request->details,
            ]);

            $voucherType = 'Bank Transfer';

            BankLedger::where('voucher_id', $bankTransfer->bank_transfer_id)
                ->where('voucher_type', $voucherType)
                ->delete();

            $details = trim((string) $request->details);

            BankLedger::create([
                'bank_id' => $fromBank->bank_id,
                'withdrawal_amount' => $request->amount,
                'deposit_amount' => 0,
                'voucher_id' => $bankTransfer->bank_transfer_id,
                'voucher_type' => $voucherType,
                'date_added' => $request->date_added,
                'details' => trim('To Account: ' . $toBank->bank_name . ($details ? (', ' . $details) : '')),
                'user_id' => auth()->id(),
            ]);

            BankLedger::create([
                'bank_id' => $toBank->bank_id,
                'deposit_amount' => $request->amount,
                'withdrawal_amount' => 0,
                'voucher_id' => $bankTransfer->bank_transfer_id,
                'voucher_type' => $voucherType,
                'date_added' => $request->date_added,
                'details' => trim('From Account: ' . $fromBank->bank_name . ($details ? (', ' . $details) : '')),
                'user_id' => auth()->id(),
            ]);

            if ($request->hasFile('attachments')) {
                $attachmentTitles = $request->input('attachment_titles', []);

                foreach ($request->file('attachments') as $index => $file) {
                    $filePath = $file->store('bank_transfers', 'public');

                    BankTransferAttachment::create([
                        'bank_transfer_id' => $bankTransfer->bank_transfer_id,
                        'file_title' => $attachmentTitles[$index] ?? null,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('bank-transfers.index')
                ->with('success', 'Bank Transfer has been updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update bank transfer: ' . $e->getMessage());
        }
    }

    public function destroy(BankTransfer $bankTransfer)
    {
        $businessId = session('active_business');
        if (!$businessId || $bankTransfer->business_id != $businessId) {
            abort(403, 'Unauthorized access to this bank transfer.');
        }

        DB::beginTransaction();
        try {
            $voucherType = 'Bank Transfer';

            BankLedger::where('voucher_id', $bankTransfer->bank_transfer_id)
                ->where('voucher_type', $voucherType)
                ->delete();

            $bankTransfer->load('attachments');
            foreach ($bankTransfer->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            $bankTransfer->delete();

            DB::commit();

            return redirect()->route('bank-transfers.index')
                ->with('success', 'Bank Transfer has been deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete bank transfer: ' . $e->getMessage());
        }
    }

    public function deleteAttachment($attachmentId)
    {
        $attachment = BankTransferAttachment::findOrFail($attachmentId);
        $transfer = $attachment->bankTransfer;

        $businessId = session('active_business');
        if (!$businessId || $transfer->business_id != $businessId) {
            abort(403, 'Unauthorized access.');
        }

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return response()->json(['success' => true, 'message' => 'Attachment deleted successfully.']);
    }

    public function print(BankTransfer $bankTransfer)
    {
        $businessId = session('active_business');
        if (!$businessId || $bankTransfer->business_id != $businessId) {
            abort(403, 'Unauthorized access to this bank transfer.');
        }

        $business = Business::find($businessId);
        $bankTransfer->load(['fromBank.currency', 'toBank.currency', 'user', 'attachments']);

        return view('bank-transfers.print', compact('bankTransfer', 'business'));
    }
}


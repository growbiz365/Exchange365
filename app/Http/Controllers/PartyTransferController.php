<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartyTransferRequest;
use App\Models\PartyTransfer;
use App\Models\PartyTransferAttachment;
use App\Models\Party;
use App\Models\PartyLedger;
use App\Models\Currency;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PartyTransferController extends Controller
{
    public function index(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $query = PartyTransfer::with(['creditParty', 'debitParty', 'creditCurrency', 'debitCurrency'])
            ->forBusiness($businessId);

        // Search filters
        if ($request->filled('party_transfer_id')) {
            $query->where('party_transfer_id', $request->party_transfer_id);
        }

        if ($request->filled('date_from')) {
            $query->where('date_added', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date_added', '<=', $request->date_to);
        }

        $transfers = $query->orderBy('party_transfer_id', 'desc')->paginate(15);

        return view('party-transfers.index', compact('transfers'));
    }

    public function show(PartyTransfer $partyTransfer)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }
        if ($partyTransfer->business_id != $businessId) {
            abort(403, 'Unauthorized access to this party transfer.');
        }

        $partyTransfer->load([
            'creditParty',
            'debitParty',
            'creditCurrency',
            'debitCurrency',
            'user',
            'attachments',
        ]);

        return view('party-transfers.show', compact('partyTransfer'));
    }

    public function create()
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $parties = Party::forBusiness($businessId)
            ->where('status', 1)
            ->orderBy('party_name')
            ->get();

        $currencies = Currency::where('status', 1)->get();

        return view('party-transfers.create', compact('parties', 'currencies'));
    }

    public function store(PartyTransferRequest $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        DB::beginTransaction();

        try {
            // Create party transfer
            $transfer = PartyTransfer::create([
                'business_id' => $businessId,
                'date_added' => $request->date_added,
                'rate' => $request->rate,
                'transaction_operation' => $request->transaction_operation ?? 1,
                'credit_party' => $request->credit_party,
                'credit_currency_id' => $request->credit_currency_id,
                'credit_amount' => $request->credit_amount,
                'debit_party' => $request->debit_party,
                'debit_currency_id' => $request->debit_currency_id,
                'debit_amount' => $request->debit_amount,
                'details' => $request->details,
                'user_id' => auth()->id(),
            ]);

            // Get party names for ledger descriptions
            $debitPartyName = Party::find($request->debit_party)->party_name;
            $creditPartyName = Party::find($request->credit_party)->party_name;

            // Create ledger entry for credit party
            PartyLedger::create([
                'party_id' => $request->credit_party,
                'credit_amount' => $request->credit_amount,
                'debit_amount' => 0,
                'currency_id' => $request->credit_currency_id,
                'voucher_id' => $transfer->party_transfer_id,
                'voucher_type' => 'Party Transfer',
                'date_added' => $request->date_added,
                'transaction_party' => "Debit Party: {$debitPartyName}",
                'rate' => $request->rate,
                'details' => $request->details,
                'user_id' => auth()->id(),
            ]);

            // Create ledger entry for debit party
            PartyLedger::create([
                'party_id' => $request->debit_party,
                'debit_amount' => $request->debit_amount,
                'credit_amount' => 0,
                'currency_id' => $request->debit_currency_id,
                'voucher_id' => $transfer->party_transfer_id,
                'voucher_type' => 'Party Transfer',
                'date_added' => $request->date_added,
                'transaction_party' => "Credit Party: {$creditPartyName}",
                'rate' => $request->rate,
                'details' => $request->details,
                'user_id' => auth()->id(),
            ]);

            // Handle file attachments
            if ($request->hasFile('attachments')) {
                $attachmentTitles = $request->input('attachment_titles', []);
                
                foreach ($request->file('attachments') as $index => $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->store('party_transfers', 'public');

                    PartyTransferAttachment::create([
                        'party_transfer_id' => $transfer->party_transfer_id,
                        'file_title' => $attachmentTitles[$index] ?? null,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('party-transfers.index')
                ->with('success', 'Party Transfer has been added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create party transfer: ' . $e->getMessage());
        }
    }

    public function edit(PartyTransfer $partyTransfer)
    {
        $businessId = session('active_business');
        if ($partyTransfer->business_id != $businessId) {
            abort(403, 'Unauthorized access to this party transfer.');
        }

        $parties = Party::forBusiness($businessId)
            ->where('status', 1)
            ->orderBy('party_name')
            ->get();

        $currencies = Currency::where('status', 1)->get();

        $partyTransfer->load('attachments');

        return view('party-transfers.edit', compact('partyTransfer', 'parties', 'currencies'));
    }

    public function update(PartyTransferRequest $request, PartyTransfer $partyTransfer)
    {
        $businessId = session('active_business');
        if ($partyTransfer->business_id != $businessId) {
            abort(403, 'Unauthorized access to this party transfer.');
        }

        DB::beginTransaction();

        try {
            // Update party transfer
            $partyTransfer->update([
                'date_added' => $request->date_added,
                'rate' => $request->rate,
                'transaction_operation' => $request->transaction_operation ?? 1,
                'credit_party' => $request->credit_party,
                'credit_currency_id' => $request->credit_currency_id,
                'credit_amount' => $request->credit_amount,
                'debit_party' => $request->debit_party,
                'debit_currency_id' => $request->debit_currency_id,
                'debit_amount' => $request->debit_amount,
                'details' => $request->details,
            ]);

            // Delete old ledger entries
            PartyLedger::where('voucher_id', $partyTransfer->party_transfer_id)
                ->where('voucher_type', 'Party Transfer')
                ->delete();

            // Get party names for ledger descriptions
            $debitPartyName = Party::find($request->debit_party)->party_name;
            $creditPartyName = Party::find($request->credit_party)->party_name;

            // Create new ledger entry for credit party
            PartyLedger::create([
                'party_id' => $request->credit_party,
                'credit_amount' => $request->credit_amount,
                'debit_amount' => 0,
                'currency_id' => $request->credit_currency_id,
                'voucher_id' => $partyTransfer->party_transfer_id,
                'voucher_type' => 'Party Transfer',
                'date_added' => $request->date_added,
                'transaction_party' => "Debit Party: {$debitPartyName}",
                'rate' => $request->rate,
                'details' => $request->details,
                'user_id' => auth()->id(),
            ]);

            // Create new ledger entry for debit party
            PartyLedger::create([
                'party_id' => $request->debit_party,
                'debit_amount' => $request->debit_amount,
                'credit_amount' => 0,
                'currency_id' => $request->debit_currency_id,
                'voucher_id' => $partyTransfer->party_transfer_id,
                'voucher_type' => 'Party Transfer',
                'date_added' => $request->date_added,
                'transaction_party' => "Credit Party: {$creditPartyName}",
                'rate' => $request->rate,
                'details' => $request->details,
                'user_id' => auth()->id(),
            ]);

            // Handle new file attachments
            if ($request->hasFile('attachments')) {
                $attachmentTitles = $request->input('attachment_titles', []);
                
                foreach ($request->file('attachments') as $index => $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->store('party_transfers', 'public');

                    PartyTransferAttachment::create([
                        'party_transfer_id' => $partyTransfer->party_transfer_id,
                        'file_title' => $attachmentTitles[$index] ?? null,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('party-transfers.index')
                ->with('success', 'Party Transfer has been updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update party transfer: ' . $e->getMessage());
        }
    }

    public function destroy(PartyTransfer $partyTransfer)
    {
        $businessId = session('active_business');
        if ($partyTransfer->business_id != $businessId) {
            abort(403, 'Unauthorized access to this party transfer.');
        }

        DB::beginTransaction();

        try {
            // Delete ledger entries
            PartyLedger::where('voucher_id', $partyTransfer->party_transfer_id)
                ->where('voucher_type', 'Party Transfer')
                ->delete();

            // Delete attachments from storage
            foreach ($partyTransfer->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            // Delete party transfer (attachments will be deleted via cascade)
            $partyTransfer->delete();

            DB::commit();

            return redirect()->route('party-transfers.index')
                ->with('success', 'Party Transfer has been deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete party transfer: ' . $e->getMessage());
        }
    }

    public function deleteAttachment($attachmentId)
    {
        $attachment = PartyTransferAttachment::findOrFail($attachmentId);
        $transfer = $attachment->partyTransfer;

        $businessId = session('active_business');
        if ($transfer->business_id != $businessId) {
            abort(403, 'Unauthorized access.');
        }

        // Delete file from storage
        Storage::disk('public')->delete($attachment->file_path);

        // Delete record
        $attachment->delete();

        return response()->json(['success' => true, 'message' => 'Attachment deleted successfully.']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoneyExchangeRequest;
use App\Models\Bank;
use App\Models\BankLedger;
use App\Models\Business;
use App\Models\CurrencyPurchase;
use App\Models\MoneyExchange;
use App\Models\MoneyExchangeAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MoneyExchangeController extends Controller
{
    public function index(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $query = MoneyExchange::with(['fromBank.currency', 'toBank.currency'])
            ->forBusiness($businessId);

        if ($request->filled('money_exchange_id')) {
            $query->where('money_exchange_id', $request->money_exchange_id);
        }
        if ($request->filled('date_from')) {
            $query->where('date_added', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date_added', '<=', $request->date_to);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('fromBank', fn ($qb) => $qb->where('bank_name', 'like', "%{$search}%"))
                    ->orWhereHas('toBank', fn ($qb) => $qb->where('bank_name', 'like', "%{$search}%"))
                    ->orWhere('details', 'like', "%{$search}%");
            });
        }

        $exchanges = $query->orderByDesc('money_exchange_id')->paginate(15);

        return view('money-exchanges.index', compact('exchanges'));
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

        return view('money-exchanges.create', compact('banks'));
    }

    public function store(MoneyExchangeRequest $request)
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

            $available = (float) BankLedger::where('bank_id', $fromBank->bank_id)
                ->selectRaw('COALESCE(SUM(deposit_amount), 0) - COALESCE(SUM(withdrawal_amount), 0) as balance')
                ->value('balance');

            if ((float) $request->debit_amount > $available) {
                return back()->withInput()->with('error', 'Insufficient Balance in Bank Account.');
            }

            $exchange = MoneyExchange::create([
                'business_id' => $businessId,
                'date_added' => $request->date_added,
                'from_account_id' => $fromBank->bank_id,
                'to_account_id' => $toBank->bank_id,
                'transaction_operation' => $request->transaction_operation,
                'debit_amount' => $request->debit_amount,
                'credit_amount' => $request->credit_amount,
                'rate' => $request->rate,
                'details' => $request->details,
                'user_id' => auth()->id(),
            ]);

            $voucherType = 'Money Exchange';

            $bankDetails = "To Account:{$toBank->bank_name} (" . number_format($request->credit_amount, 2) . ") Rate:{$request->rate}";
            BankLedger::create([
                'business_id' => $businessId,
                'bank_id' => $fromBank->bank_id,
                'withdrawal_amount' => $request->debit_amount,
                'deposit_amount' => 0,
                'voucher_id' => $exchange->money_exchange_id,
                'voucher_type' => $voucherType,
                'date_added' => $request->date_added,
                'details' => $bankDetails . ($request->details ? ', ' . $request->details : ''),
                'user_id' => auth()->id(),
            ]);

            $bankDetails = "From Account:{$fromBank->bank_name} (" . number_format($request->debit_amount, 2) . ") Rate:{$request->rate}";
            BankLedger::create([
                'business_id' => $businessId,
                'bank_id' => $toBank->bank_id,
                'deposit_amount' => $request->credit_amount,
                'withdrawal_amount' => 0,
                'voucher_id' => $exchange->money_exchange_id,
                'voucher_type' => $voucherType,
                'date_added' => $request->date_added,
                'details' => $bankDetails . ($request->details ? ', ' . $request->details : ''),
                'user_id' => auth()->id(),
            ]);

            CurrencyPurchase::create([
                'business_id' => $businessId,
                'currency_id' => $toBank->currency_id,
                'date_added' => $request->date_added,
                'currency_amount' => $request->credit_amount,
                'unit_cost' => $request->rate,
                'voucher_id' => $exchange->money_exchange_id,
                'voucher_type' => $voucherType,
                'user_id' => auth()->id(),
            ]);

            if ($request->hasFile('attachments')) {
                $attachmentTitles = $request->input('attachment_titles', []);

                foreach ($request->file('attachments') as $index => $file) {
                    $filePath = $file->store('money_exchanges', 'public');

                    MoneyExchangeAttachment::create([
                        'money_exchange_id' => $exchange->money_exchange_id,
                        'file_title' => $attachmentTitles[$index] ?? null,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('money-exchanges.index')
                ->with('success', 'Money Exchange has been added successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create money exchange: ' . $e->getMessage());
        }
    }

    public function show(MoneyExchange $moneyExchange)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }
        if ($moneyExchange->business_id != $businessId) {
            abort(403, 'Unauthorized access to this money exchange.');
        }

        $moneyExchange->load([
            'fromBank.currency',
            'toBank.currency',
            'user',
            'attachments',
        ]);

        return view('money-exchanges.show', compact('moneyExchange'));
    }

    public function edit(MoneyExchange $moneyExchange)
    {
        $businessId = session('active_business');
        if ($moneyExchange->business_id != $businessId) {
            abort(403, 'Unauthorized access to this money exchange.');
        }

        $banks = Bank::forBusiness($businessId)
            ->active()
            ->with('currency')
            ->orderBy('bank_type_id')
            ->orderBy('bank_name')
            ->get();

        $moneyExchange->load('attachments');

        return view('money-exchanges.edit', compact('moneyExchange', 'banks'));
    }

    public function update(MoneyExchangeRequest $request, MoneyExchange $moneyExchange)
    {
        $businessId = session('active_business');
        if ($moneyExchange->business_id != $businessId) {
            abort(403, 'Unauthorized access to this money exchange.');
        }

        DB::beginTransaction();
        try {
            $fromBank = Bank::forBusiness($businessId)->active()->findOrFail($request->from_account_id);
            $toBank = Bank::forBusiness($businessId)->active()->findOrFail($request->to_account_id);

            $available = (float) BankLedger::where('bank_id', $fromBank->bank_id)
                ->selectRaw('COALESCE(SUM(deposit_amount), 0) - COALESCE(SUM(withdrawal_amount), 0) as balance')
                ->value('balance');

            $oldDebit = (float) $moneyExchange->debit_amount;
            $oldFromId = (int) $moneyExchange->from_account_id;

            $effectiveAvailable = $fromBank->bank_id === $oldFromId ? $available + $oldDebit : $available;

            if ((float) $request->debit_amount > $effectiveAvailable) {
                return back()->withInput()->with('error', 'Insufficient Balance in Bank Account.');
            }

            $moneyExchange->update([
                'date_added' => $request->date_added,
                'from_account_id' => $fromBank->bank_id,
                'to_account_id' => $toBank->bank_id,
                'transaction_operation' => $request->transaction_operation,
                'debit_amount' => $request->debit_amount,
                'credit_amount' => $request->credit_amount,
                'rate' => $request->rate,
                'details' => $request->details,
            ]);

            $voucherType = 'Money Exchange';

            BankLedger::where('voucher_id', $moneyExchange->money_exchange_id)
                ->where('voucher_type', $voucherType)
                ->delete();

            CurrencyPurchase::where('voucher_id', $moneyExchange->money_exchange_id)
                ->where('voucher_type', $voucherType)
                ->delete();

            $bankDetails = "To Account:{$toBank->bank_name} (" . number_format($request->credit_amount, 2) . ") Rate:{$request->rate}";
            BankLedger::create([
                'business_id' => $businessId,
                'bank_id' => $fromBank->bank_id,
                'withdrawal_amount' => $request->debit_amount,
                'deposit_amount' => 0,
                'voucher_id' => $moneyExchange->money_exchange_id,
                'voucher_type' => $voucherType,
                'date_added' => $request->date_added,
                'details' => $bankDetails . ($request->details ? ', ' . $request->details : ''),
                'user_id' => auth()->id(),
            ]);

            $bankDetails = "From Account:{$fromBank->bank_name} (" . number_format($request->debit_amount, 2) . ") Rate:{$request->rate}";
            BankLedger::create([
                'business_id' => $businessId,
                'bank_id' => $toBank->bank_id,
                'deposit_amount' => $request->credit_amount,
                'withdrawal_amount' => 0,
                'voucher_id' => $moneyExchange->money_exchange_id,
                'voucher_type' => $voucherType,
                'date_added' => $request->date_added,
                'details' => $bankDetails . ($request->details ? ', ' . $request->details : ''),
                'user_id' => auth()->id(),
            ]);

            CurrencyPurchase::create([
                'business_id' => $businessId,
                'currency_id' => $toBank->currency_id,
                'date_added' => $request->date_added,
                'currency_amount' => $request->credit_amount,
                'unit_cost' => $request->rate,
                'voucher_id' => $moneyExchange->money_exchange_id,
                'voucher_type' => $voucherType,
                'user_id' => auth()->id(),
            ]);

            if ($request->hasFile('attachments')) {
                $attachmentTitles = $request->input('attachment_titles', []);

                foreach ($request->file('attachments') as $index => $file) {
                    $filePath = $file->store('money_exchanges', 'public');

                    MoneyExchangeAttachment::create([
                        'money_exchange_id' => $moneyExchange->money_exchange_id,
                        'file_title' => $attachmentTitles[$index] ?? null,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('money-exchanges.index')
                ->with('success', 'Money Exchange has been updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update money exchange: ' . $e->getMessage());
        }
    }

    public function destroy(MoneyExchange $moneyExchange)
    {
        $businessId = session('active_business');
        if ($moneyExchange->business_id != $businessId) {
            abort(403, 'Unauthorized access to this money exchange.');
        }

        DB::beginTransaction();
        try {
            $voucherType = 'Money Exchange';

            BankLedger::where('voucher_id', $moneyExchange->money_exchange_id)
                ->where('voucher_type', $voucherType)
                ->delete();

            CurrencyPurchase::where('voucher_id', $moneyExchange->money_exchange_id)
                ->where('voucher_type', $voucherType)
                ->delete();

            $moneyExchange->load('attachments');
            foreach ($moneyExchange->attachments as $attachment) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($attachment->file_path);
            }

            $moneyExchange->delete();

            DB::commit();

            return redirect()->route('money-exchanges.index')
                ->with('success', 'Money Exchange has been deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete money exchange: ' . $e->getMessage());
        }
    }

    public function deleteAttachment($attachmentId)
    {
        $attachment = MoneyExchangeAttachment::findOrFail($attachmentId);
        $exchange = $attachment->moneyExchange;

        $businessId = session('active_business');
        if (!$businessId || $exchange->business_id != $businessId) {
            abort(403, 'Unauthorized access.');
        }

        \Illuminate\Support\Facades\Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return response()->json(['success' => true, 'message' => 'Attachment deleted successfully.']);
    }
}


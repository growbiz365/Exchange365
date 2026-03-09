<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Bank;
use App\Models\BankLedger;
use App\Models\Business;
use App\Models\Currency;
use App\Models\Party;
use App\Models\PartyLedger;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $query = Purchase::with(['bank.currency', 'party', 'partyCurrency', 'user'])
            ->forBusiness($businessId);

        if ($request->filled('purchase_id')) {
            $query->where('purchase_id', $request->purchase_id);
        }
        if ($request->filled('bank_id')) {
            $query->where('bank_id', $request->bank_id);
        }
        if ($request->filled('date_from')) {
            $query->where('date_added', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date_added', '<=', $request->date_to);
        }

        $purchases = $query->orderByDesc('purchase_id')->paginate(15)->withQueryString();

        $banks = Bank::forBusiness($businessId)->active()->orderBy('bank_name')->get(['bank_id', 'bank_name']);

        return view('purchases.index', compact('purchases', 'banks'));
    }

    /**
     * Purchase dashboard with summary stats and quick links.
     */
    public function dashboard(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $totalPurchases = Purchase::forBusiness($businessId)->count();
        $totalCredit = Purchase::forBusiness($businessId)->sum('credit_amount');
        $totalDebit = Purchase::forBusiness($businessId)->sum('debit_amount');

        $recentPurchases = Purchase::with(['bank', 'party', 'partyCurrency'])
            ->forBusiness($businessId)
            ->orderByDesc('purchase_id')
            ->limit(7)
            ->get();

        return view('purchases.dashboard', compact(
            'totalPurchases',
            'totalCredit',
            'totalDebit',
            'recentPurchases'
        ));
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
        $currencies = Currency::active()->orderBy('currency')->get(['currency_id', 'currency', 'currency_symbol']);

        return view('purchases.create', compact('banks', 'parties', 'currencies'));
    }

    public function store(PurchaseRequest $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $bank = Bank::forBusiness($businessId)->active()->findOrFail($request->bank_id);
        $party = Party::forBusiness($businessId)->active()->findOrFail($request->party_id);

        DB::beginTransaction();
        try {
            $purchase = Purchase::create([
                'business_id' => $businessId,
                'date_added' => $request->date_added,
                'bank_id' => $bank->bank_id,
                'party_id' => $party->party_id,
                'party_currency_id' => $request->party_currency_id,
                'transaction_operation' => (int) ($request->transaction_operation ?? 1),
                'credit_amount' => $request->credit_amount,
                'rate' => $request->rate,
                'debit_amount' => $request->debit_amount,
                'details' => $request->details,
                'user_id' => auth()->id(),
            ]);

            $this->syncLedgersForPurchase($purchase, $bank, $party);

            DB::commit();
            return redirect()->route('purchases.index')
                ->with('success', 'Purchase has been added.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create purchase: ' . $e->getMessage());
        }
    }

    public function edit(Purchase $purchase)
    {
        $businessId = session('active_business');
        if (!$businessId || $purchase->business_id != $businessId) {
            abort(403, 'Unauthorized access to this purchase.');
        }

        $banks = Bank::forBusiness($businessId)->active()->with('currency')->orderBy('bank_name')->get();
        $parties = Party::forBusiness($businessId)->active()->orderBy('party_name')->get();
        $currencies = Currency::active()->orderBy('currency')->get(['currency_id', 'currency', 'currency_symbol']);
        $purchase->load(['bank', 'party', 'partyCurrency']);

        return view('purchases.edit', compact('purchase', 'banks', 'parties', 'currencies'));
    }

    public function show(Purchase $purchase)
    {
        $businessId = session('active_business');
        if (!$businessId || $purchase->business_id != $businessId) {
            abort(403, 'Unauthorized access to this purchase.');
        }

        $purchase->load(['bank.currency', 'party', 'partyCurrency', 'user']);

        return view('purchases.show', compact('purchase'));
    }

    public function update(PurchaseRequest $request, Purchase $purchase)
    {
        $businessId = session('active_business');
        if (!$businessId || $purchase->business_id != $businessId) {
            abort(403, 'Unauthorized access to this purchase.');
        }

        $bank = Bank::forBusiness($businessId)->active()->findOrFail($request->bank_id);
        $party = Party::forBusiness($businessId)->active()->findOrFail($request->party_id);

        DB::beginTransaction();
        try {
            $purchase->update([
                'date_added' => $request->date_added,
                'bank_id' => $bank->bank_id,
                'party_id' => $party->party_id,
                'party_currency_id' => $request->party_currency_id,
                'transaction_operation' => (int) ($request->transaction_operation ?? 1),
                'credit_amount' => $request->credit_amount,
                'rate' => $request->rate,
                'debit_amount' => $request->debit_amount,
                'details' => $request->details,
            ]);

            BankLedger::where('voucher_id', $purchase->purchase_id)
                ->where('voucher_type', Purchase::VOUCHER_TYPE)
                ->delete();
            PartyLedger::where('voucher_id', $purchase->purchase_id)
                ->where('voucher_type', Purchase::VOUCHER_TYPE)
                ->delete();

            $this->syncLedgersForPurchase($purchase->fresh(), $bank, $party);

            DB::commit();
            return redirect()->route('purchases.index')
                ->with('success', 'Purchase has been updated.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update purchase: ' . $e->getMessage());
        }
    }

    public function destroy(Purchase $purchase)
    {
        $businessId = session('active_business');
        if (!$businessId || $purchase->business_id != $businessId) {
            abort(403, 'Unauthorized access to this purchase.');
        }

        DB::beginTransaction();
        try {
            BankLedger::where('voucher_id', $purchase->purchase_id)
                ->where('voucher_type', Purchase::VOUCHER_TYPE)
                ->delete();
            PartyLedger::where('voucher_id', $purchase->purchase_id)
                ->where('voucher_type', Purchase::VOUCHER_TYPE)
                ->delete();

            $purchase->delete();

            DB::commit();
            return redirect()->route('purchases.index')
                ->with('success', 'Purchase has been deleted.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete purchase: ' . $e->getMessage());
        }
    }

    public function print(Purchase $purchase)
    {
        $businessId = session('active_business');
        if (!$businessId || $purchase->business_id != $businessId) {
            abort(403, 'Unauthorized access to this purchase.');
        }

        $business = Business::find($businessId);
        $purchase->load(['bank.currency', 'party', 'partyCurrency', 'user']);

        return view('purchases.print', compact('purchase', 'business'));
    }

    protected function syncLedgersForPurchase(Purchase $purchase, Bank $bank, Party $party): void
    {
        $details = trim((string) $purchase->details);
        $creditAmount = (float) $purchase->credit_amount;
        $debitAmount = (float) $purchase->debit_amount;
        $rate = (float) $purchase->rate;
        $dateAdded = $purchase->date_added;
        $voucherType = Purchase::VOUCHER_TYPE;
        $voucherId = $purchase->purchase_id;

        $bankDetails = 'Party: ' . $party->party_name . ', Rate ' . $rate . ($details ? ', ' . $details : '');
        BankLedger::create([
            'business_id' => $purchase->business_id,
            'bank_id' => $bank->bank_id,
            'voucher_id' => $voucherId,
            'voucher_type' => $voucherType,
            'date_added' => $dateAdded,
            'details' => $bankDetails,
            'user_id' => auth()->id(),
            'deposit_amount' => $creditAmount,
            'withdrawal_amount' => 0,
        ]);

        $moreDetails = $bank->bank_name . ', ' . number_format($creditAmount, 2) . ', Rate ' . $rate . ($details ? ', ' . $details : '');
        PartyLedger::create([
            'business_id' => $purchase->business_id,
            'party_id' => $party->party_id,
            'currency_id' => $purchase->party_currency_id,
            'voucher_id' => $voucherId,
            'voucher_type' => $voucherType,
            'date_added' => $dateAdded,
            'details' => $moreDetails,
            'user_id' => auth()->id(),
            'credit_amount' => $debitAmount,
            'debit_amount' => 0,
        ]);
    }
}

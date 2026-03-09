<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesRequest;
use App\Models\Bank;
use App\Models\BankLedger;
use App\Models\Business;
use App\Models\Currency;
use App\Models\Party;
use App\Models\PartyLedger;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $query = Sale::with(['bank.currency', 'party', 'partyCurrency', 'user'])
            ->forBusiness($businessId);

        if ($request->filled('sales_id')) {
            $query->where('sales_id', $request->sales_id);
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

        $sales = $query->orderByDesc('sales_id')->paginate(15)->withQueryString();

        $banks = Bank::forBusiness($businessId)->active()->orderBy('bank_name')->get(['bank_id', 'bank_name']);

        return view('sales.index', compact('sales', 'banks'));
    }

    /**
     * Sales dashboard with summary stats and quick links.
     */
    public function dashboard(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $totalSales = Sale::forBusiness($businessId)->count();
        $totalCurrencyAmount = Sale::forBusiness($businessId)->sum('currency_amount');
        $totalPartyAmount = Sale::forBusiness($businessId)->sum('party_amount');

        $recentSales = Sale::with(['bank', 'party', 'partyCurrency'])
            ->forBusiness($businessId)
            ->orderByDesc('sales_id')
            ->limit(7)
            ->get();

        return view('sales.dashboard', compact(
            'totalSales',
            'totalCurrencyAmount',
            'totalPartyAmount',
            'recentSales'
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

        return view('sales.create', compact('banks', 'parties', 'currencies'));
    }

    public function store(SalesRequest $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $bank = Bank::forBusiness($businessId)->active()->findOrFail($request->bank_id);
        $party = Party::forBusiness($businessId)->active()->findOrFail($request->party_id);

        $balance = (float) BankLedger::where('bank_id', $bank->bank_id)
            ->selectRaw('COALESCE(SUM(deposit_amount), 0) - COALESCE(SUM(withdrawal_amount), 0) as balance')
            ->value('balance');
        if ((float) $request->currency_amount > $balance) {
            return back()->withInput()->with('error', 'Insufficient currency amount in bank account.');
        }

        DB::beginTransaction();
        try {
            $sale = Sale::create([
                'business_id' => $businessId,
                'date_added' => $request->date_added,
                'bank_id' => $bank->bank_id,
                'party_id' => $party->party_id,
                'party_currency_id' => $request->party_currency_id,
                'transaction_operation' => (int) ($request->transaction_operation ?? 1),
                'currency_amount' => $request->currency_amount,
                'rate' => $request->rate,
                'party_amount' => $request->party_amount,
                'details' => $request->details,
                'user_id' => auth()->id(),
            ]);

            $this->syncLedgersForSale($sale, $bank, $party);

            DB::commit();
            return redirect()->route('sales.index')
                ->with('success', 'Sales has been added.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create sales: ' . $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        $businessId = session('active_business');
        if (!$businessId || $sale->business_id != $businessId) {
            abort(403, 'Unauthorized access to this sale.');
        }

        $sale->load(['bank.currency', 'party', 'partyCurrency', 'user']);

        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $businessId = session('active_business');
        if (!$businessId || $sale->business_id != $businessId) {
            abort(403, 'Unauthorized access to this sale.');
        }

        $banks = Bank::forBusiness($businessId)->active()->with('currency')->orderBy('bank_name')->get();
        $parties = Party::forBusiness($businessId)->active()->orderBy('party_name')->get();
        $currencies = Currency::active()->orderBy('currency')->get(['currency_id', 'currency', 'currency_symbol']);
        $sale->load(['bank', 'party', 'partyCurrency']);

        return view('sales.edit', compact('sale', 'banks', 'parties', 'currencies'));
    }

    public function update(SalesRequest $request, Sale $sale)
    {
        $businessId = session('active_business');
        if (!$businessId || $sale->business_id != $businessId) {
            abort(403, 'Unauthorized access to this sale.');
        }

        $bank = Bank::forBusiness($businessId)->active()->findOrFail($request->bank_id);
        $party = Party::forBusiness($businessId)->active()->findOrFail($request->party_id);

        $balance = (float) BankLedger::where('bank_id', $bank->bank_id)
            ->selectRaw('COALESCE(SUM(deposit_amount), 0) - COALESCE(SUM(withdrawal_amount), 0) as balance')
            ->value('balance');
        $oldAmount = (int) $sale->bank_id === (int) $bank->bank_id ? (float) $sale->currency_amount : 0;
        if ((float) $request->currency_amount > $balance + $oldAmount) {
            return back()->withInput()->with('error', 'Insufficient currency amount in bank account.');
        }

        DB::beginTransaction();
        try {
            $sale->update([
                'date_added' => $request->date_added,
                'bank_id' => $bank->bank_id,
                'party_id' => $party->party_id,
                'party_currency_id' => $request->party_currency_id,
                'transaction_operation' => (int) ($request->transaction_operation ?? 1),
                'currency_amount' => $request->currency_amount,
                'rate' => $request->rate,
                'party_amount' => $request->party_amount,
                'details' => $request->details,
            ]);

            BankLedger::where('voucher_id', $sale->sales_id)
                ->where('voucher_type', Sale::VOUCHER_TYPE)
                ->delete();
            PartyLedger::where('voucher_id', $sale->sales_id)
                ->where('voucher_type', Sale::VOUCHER_TYPE)
                ->delete();

            $this->syncLedgersForSale($sale->fresh(), $bank, $party);

            DB::commit();
            return redirect()->route('sales.index')
                ->with('success', 'Sales has been updated.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update sales: ' . $e->getMessage());
        }
    }

    public function destroy(Sale $sale)
    {
        $businessId = session('active_business');
        if (!$businessId || $sale->business_id != $businessId) {
            abort(403, 'Unauthorized access to this sale.');
        }

        DB::beginTransaction();
        try {
            BankLedger::where('voucher_id', $sale->sales_id)
                ->where('voucher_type', Sale::VOUCHER_TYPE)
                ->delete();
            PartyLedger::where('voucher_id', $sale->sales_id)
                ->where('voucher_type', Sale::VOUCHER_TYPE)
                ->delete();

            $sale->delete();

            DB::commit();
            return redirect()->route('sales.index')
                ->with('success', 'Sales has been deleted.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete sales: ' . $e->getMessage());
        }
    }

    public function print(Sale $sale)
    {
        $businessId = session('active_business');
        if (!$businessId || $sale->business_id != $businessId) {
            abort(403, 'Unauthorized access to this sale.');
        }

        $business = Business::find($businessId);
        $sale->load(['bank.currency', 'party', 'partyCurrency', 'user']);

        return view('sales.print', compact('sale', 'business'));
    }

    protected function syncLedgersForSale(Sale $sale, Bank $bank, Party $party): void
    {
        $details = trim((string) $sale->details);
        $currencyAmount = (float) $sale->currency_amount;
        $partyAmount = (float) $sale->party_amount;
        $rate = (float) $sale->rate;
        $dateAdded = $sale->date_added;
        $voucherType = Sale::VOUCHER_TYPE;
        $voucherId = $sale->sales_id;

        $bankDetails = 'Party: ' . $party->party_name . ', Rate ' . $rate . ($details ? ', ' . $details : '');
        BankLedger::create([
            'business_id' => $sale->business_id,
            'bank_id' => $bank->bank_id,
            'voucher_id' => $voucherId,
            'voucher_type' => $voucherType,
            'date_added' => $dateAdded,
            'details' => $bankDetails,
            'user_id' => auth()->id(),
            'deposit_amount' => 0,
            'withdrawal_amount' => $currencyAmount,
        ]);

        $moreDetails = $bank->bank_name . ', ' . number_format($currencyAmount, 2) . ' ' . ($bank->currency->currency_symbol ?? '') . ', Rate ' . $rate . ($details ? ', ' . $details : '');
        PartyLedger::create([
            'business_id' => $sale->business_id,
            'party_id' => $party->party_id,
            'currency_id' => $sale->party_currency_id,
            'voucher_id' => $voucherId,
            'voucher_type' => $voucherType,
            'date_added' => $dateAdded,
            'details' => $moreDetails,
            'user_id' => auth()->id(),
            'credit_amount' => 0,
            'debit_amount' => $partyAmount,
        ]);
    }
}

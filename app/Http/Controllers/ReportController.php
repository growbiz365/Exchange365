<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Bank;
use App\Models\BankLedger;
use App\Models\BankTransfer;
use App\Models\GeneralVoucher;
use App\Models\Party;
use App\Models\PartyLedger;
use App\Models\PartyTransfer;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Business;
use Illuminate\Http\Request;
use App\Support\ActivityBusinessScope;
use Spatie\Activitylog\Models\Activity;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Daily Report: all vouchers for a selected date across every module.
     */
    public function dailyReport(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $dateSearch = $request->input('date_search', now()->format('Y-m-d'));
        $dateSearch = \Carbon\Carbon::parse($dateSearch)->format('Y-m-d');

        $partyTransfers = PartyTransfer::with(['creditParty', 'debitParty', 'creditCurrency', 'debitCurrency'])
            ->where('business_id', $businessId)
            ->whereDate('date_added', $dateSearch)
            ->orderBy('party_transfer_id')
            ->get();

        $bankTransfers = BankTransfer::with(['fromBank.currency', 'toBank.currency'])
            ->where('business_id', $businessId)
            ->whereDate('date_added', $dateSearch)
            ->orderBy('bank_transfer_id')
            ->get();

        $generalVouchers = GeneralVoucher::with(['bank.currency', 'party'])
            ->where('business_id', $businessId)
            ->whereDate('date_added', $dateSearch)
            ->orderBy('general_voucher_id')
            ->get();

        $purchases = Purchase::with(['bank.currency', 'party', 'partyCurrency'])
            ->where('business_id', $businessId)
            ->whereDate('date_added', $dateSearch)
            ->orderBy('purchase_id')
            ->get();

        $sales = Sale::with(['bank.currency', 'party', 'partyCurrency'])
            ->where('business_id', $businessId)
            ->whereDate('date_added', $dateSearch)
            ->orderBy('sales_id')
            ->get();

        $purchaseAssets = Asset::with(['purchaseBank', 'purchaseParty', 'category'])
            ->where('business_id', $businessId)
            ->whereDate('date_added', $dateSearch)
            ->orderBy('asset_id')
            ->get();

        $saleAssets = Asset::with(['saleBank', 'saleParty', 'category'])
            ->where('business_id', $businessId)
            ->whereNotNull('sale_date')
            ->whereDate('sale_date', $dateSearch)
            ->where('asset_status', Asset::STATUS_SOLD)
            ->orderBy('asset_id')
            ->get();

        $business = Business::find($businessId);

        return view('reports.daily-report', compact(
            'dateSearch', 'partyTransfers', 'bankTransfers', 'generalVouchers',
            'purchases', 'sales', 'purchaseAssets', 'saleAssets', 'business'
        ));
    }

    /**
     * Activity log report: who did what and when.
     */
    public function activityLog(Request $request)
    {
        $businessId = session('active_business');
        if (! $businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $query = Activity::query()
            ->with(['causer', 'subject'])
            ->where('log_name', '!=', 'auth')
            ->where(fn ($q) => ActivityBusinessScope::scopeQuery($q, (int) $businessId))
            ->orderByDesc('created_at');

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        $activities = $query->paginate(20)->withQueryString();
        $business = Business::find($businessId);

        return view('reports.activity-log', compact('activities', 'business'));
    }

    

    /**
     * Currency summary as of a given date (bank balance + party balance per currency).
     */
    public function currencySummary(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $dateSearch = $request->input('date_search', now()->format('Y-m-d'));
        $dateSearch = \Carbon\Carbon::parse($dateSearch)->format('Y-m-d');

        $currencyBalances = BankLedger::query()
            ->join('banks as b', 'b.bank_id', '=', 'bank_ledger.bank_id')
            ->join('currency as c', 'c.currency_id', '=', 'b.currency_id')
            ->where('b.business_id', $businessId)
            ->where('bank_ledger.date_added', '<=', $dateSearch)
            ->selectRaw('c.currency_id, c.currency, c.currency_symbol, COALESCE(SUM(bank_ledger.deposit_amount), 0) - COALESCE(SUM(bank_ledger.withdrawal_amount), 0) as currency_balance')
            ->groupBy('c.currency_id', 'c.currency', 'c.currency_symbol')
            ->orderBy('c.currency_id')
            ->get();

        $partyBalancesByCurrency = PartyLedger::query()
            ->join('party as p', 'p.party_id', '=', 'party_ledger.party_id')
            ->where('p.business_id', $businessId)
            ->where('party_ledger.date_added', '<=', $dateSearch)
            ->selectRaw('party_ledger.currency_id, COALESCE(SUM(party_ledger.credit_amount), 0) - COALESCE(SUM(party_ledger.debit_amount), 0) as party_balance')
            ->groupBy('party_ledger.currency_id')
            ->get()
            ->keyBy('currency_id');

        $rows = $currencyBalances->map(function ($row) use ($partyBalancesByCurrency) {
            $partyRow = $partyBalancesByCurrency->get($row->currency_id);
            $partyBalance = $partyRow ? (float) $partyRow->party_balance : 0;
            $currencyBalance = (float) $row->currency_balance;
            return (object) [
                'currency_id' => $row->currency_id,
                'currency' => $row->currency,
                'currency_symbol' => $row->currency_symbol,
                'currency_balance' => $currencyBalance,
                'party_balance' => $partyBalance,
                'total_amount' => $currencyBalance + $partyBalance,
            ];
        });

        $business = Business::find($businessId);
        return view('reports.currency-summary', compact('dateSearch', 'rows', 'business'));
    }
}

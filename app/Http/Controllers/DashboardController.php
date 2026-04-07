<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Bank;
use App\Models\BankLedger;
use App\Models\BankTransfer;
use App\Models\Business;
use App\Models\Currency;
use App\Models\GeneralVoucher;
use App\Models\MoneyExchange;
use App\Models\Party;
use App\Models\PartyLedger;
use App\Models\PartyTransfer;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $businessId = session('active_business');
        $business = $businessId ? Business::with('timezone')->find($businessId) : null;
        $businessTimezone = $business?->timezone?->timezone_name ?? 'Asia/Karachi';

        if (!$businessId) {
            return view('dashboard', [
                'stats'             => $this->emptyStats(),
                'businessTimezone'  => $businessTimezone,
                'bankBalances'      => collect(),
                'totalCredit'       => 0,
                'totalDebit'        => 0,
                'todayTransactions' => 0,
                'topParties'        => collect(),
            ]);
        }

        $stats = [
            'total_banks'             => Bank::where('business_id', $businessId)->count(),
            'total_parties'           => Party::where('business_id', $businessId)->count(),
            'total_general_vouchers'  => GeneralVoucher::where('business_id', $businessId)->count(),
            'total_assets'            => Asset::where('business_id', $businessId)->count(),
            'total_party_transfers'   => PartyTransfer::forBusiness($businessId)->count(),
            'total_money_exchanges'   => MoneyExchange::forBusiness($businessId)->count(),
            'total_bank_transfers'    => BankTransfer::forBusiness($businessId)->count(),
            'general_vouchers_amount' => (float) GeneralVoucher::forBusiness($businessId)->sum('amount'),
            'party_transfers_amount'  => (float) PartyTransfer::forBusiness($businessId)->sum('debit_amount'),
            'money_exchanges_amount'  => (float) MoneyExchange::forBusiness($businessId)->sum('debit_amount'),
            'bank_transfers_amount'   => (float) BankTransfer::forBusiness($businessId)->sum('amount'),
        ];

        // Today's date in business timezone
        $businessToday = now()->setTimezone($businessTimezone)->format('Y-m-d');

        // Today's transaction count across all 4 types
        $todayTransactions = GeneralVoucher::where('business_id', $businessId)->where('date_added', $businessToday)->count()
            + PartyTransfer::forBusiness($businessId)->where('date_added', $businessToday)->count()
            + MoneyExchange::forBusiness($businessId)->where('date_added', $businessToday)->count()
            + BankTransfer::forBusiness($businessId)->where('date_added', $businessToday)->count();

        // Bank balances (same query as BankController::bankBalances)
        $today = now()->format('Y-m-d');

        $bankBalances = BankLedger::query()
            ->join('banks as b', 'b.bank_id', '=', 'bank_ledger.bank_id')
            ->join('currency as c', 'c.currency_id', '=', 'b.currency_id')
            ->where('b.business_id', $businessId)
            ->where('bank_ledger.date_added', '<=', $today)
            ->selectRaw('
                b.bank_id,
                b.bank_name,
                c.currency,
                c.currency_symbol,
                (COALESCE(SUM(bank_ledger.deposit_amount), 0)
                 - COALESCE(SUM(bank_ledger.withdrawal_amount), 0)) as bank_balance
            ')
            ->groupBy('b.bank_id', 'b.bank_name', 'c.currency', 'c.currency_symbol', 'b.bank_type_id', 'b.currency_id')
            ->orderBy('b.bank_type_id')
            ->orderBy('b.currency_id')
            ->get();

        // Total Credit / Debit from party ledger (default currency, same logic as party balances)
        $defaultCurrency = Currency::getDefault();
        $totalCredit = 0;
        $totalDebit  = 0;
        $topParties  = collect();

        if ($defaultCurrency) {
            $partyTotals = PartyLedger::query()
                ->join('party as p', 'p.party_id', '=', 'party_ledger.party_id')
                ->where('p.business_id', $businessId)
                ->where('party_ledger.currency_id', $defaultCurrency->currency_id)
                ->where('party_ledger.date_added', '<=', $today)
                ->selectRaw('
                    COALESCE(SUM(party_ledger.credit_amount), 0) as total_credit,
                    COALESCE(SUM(party_ledger.debit_amount),  0) as total_debit
                ')
                ->first();

            $totalCredit = (float) ($partyTotals->total_credit ?? 0);
            $totalDebit  = (float) ($partyTotals->total_debit  ?? 0);

            // Top parties by absolute net balance in default currency
            $topParties = PartyLedger::query()
                ->join('party as p', 'p.party_id', '=', 'party_ledger.party_id')
                ->where('p.business_id', $businessId)
                ->where('party_ledger.currency_id', $defaultCurrency->currency_id)
                ->where('party_ledger.date_added', '<=', $today)
                ->selectRaw('
                    p.party_id,
                    p.party_name,
                    (COALESCE(SUM(party_ledger.credit_amount), 0)
                     - COALESCE(SUM(party_ledger.debit_amount), 0)) as net_balance
                ')
                ->groupBy('p.party_id', 'p.party_name')
                ->havingRaw('(COALESCE(SUM(party_ledger.credit_amount), 0) - COALESCE(SUM(party_ledger.debit_amount), 0)) != 0')
                ->orderByRaw('ABS(COALESCE(SUM(party_ledger.credit_amount), 0) - COALESCE(SUM(party_ledger.debit_amount), 0)) DESC')
                ->limit(8)
                ->get();
        }

        return view('dashboard', compact(
            'stats',
            'businessTimezone',
            'bankBalances',
            'totalCredit',
            'totalDebit',
            'todayTransactions',
            'topParties'
        ));
    }

    private function emptyStats(): array
    {
        return [
            'total_banks'             => 0,
            'total_parties'           => 0,
            'total_general_vouchers'  => 0,
            'total_assets'            => 0,
            'total_party_transfers'   => 0,
            'total_money_exchanges'   => 0,
            'total_bank_transfers'    => 0,
            'general_vouchers_amount' => 0,
            'party_transfers_amount'  => 0,
            'money_exchanges_amount'  => 0,
            'bank_transfers_amount'   => 0,
        ];
    }
}

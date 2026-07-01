<?php

namespace App\Http\Controllers;

use App\Models\BankLedger;
use App\Models\Business;
use App\Models\Currency;
use App\Models\PartyLedger;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $businessId = session('active_business');
        $business = $businessId ? Business::with('timezone')->find($businessId) : null;
        $businessTimezone = $business?->timezone?->timezone_name ?? 'Asia/Karachi';

        if (!$businessId) {
            return view('dashboard', [
                'businessTimezone'  => $businessTimezone,
                'bankBalances'      => collect(),
                'totalCredit'       => 0,
                'totalDebit'        => 0,
                'topParties'        => collect(),
            ]);
        }

        $today = now()->format('Y-m-d');

        $bankBalances = BankLedger::query()
            ->join('banks as b', 'b.bank_id', '=', 'bank_ledger.bank_id')
            ->join('currency as c', 'c.currency_id', '=', 'b.currency_id')
            ->where('b.business_id', $businessId)
            ->where('b.status', 1)
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

        // Total Credit / Debit: same meaning as Party Balances report — sums of per-party net
        // balances (receivable vs payable), not raw ledger column totals. Only party_type = 1
        // (Khata / regular parties), matching PartyController::balances.
        $defaultCurrency = Currency::getDefault();
        $totalCredit = 0;
        $totalDebit  = 0;
        $topParties  = collect();

        if ($defaultCurrency) {
            $partyNetBalances = PartyLedger::query()
                ->join('party as p', 'p.party_id', '=', 'party_ledger.party_id')
                ->where('p.business_id', $businessId)
                ->where('p.party_type', 1)
                ->where('party_ledger.currency_id', $defaultCurrency->currency_id)
                ->where('party_ledger.date_added', '<=', $today)
                ->groupBy('p.party_id')
                ->selectRaw('
                    COALESCE(SUM(party_ledger.credit_amount), 0)
                    - COALESCE(SUM(party_ledger.debit_amount), 0) as net_balance
                ')
                ->pluck('net_balance');

            foreach ($partyNetBalances as $net) {
                $net = (float) $net;
                if ($net > 0) {
                    $totalCredit += $net;
                } elseif ($net < 0) {
                    $totalDebit += abs($net);
                }
            }

            $topParties = PartyLedger::query()
                ->join('party as p', 'p.party_id', '=', 'party_ledger.party_id')
                ->where('p.business_id', $businessId)
                ->where('p.party_type', 1)
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
            'businessTimezone',
            'bankBalances',
            'totalCredit',
            'totalDebit',
            'topParties'
        ));
    }
}

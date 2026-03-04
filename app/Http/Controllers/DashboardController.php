<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Bank;
use App\Models\BankTransfer;
use App\Models\Business;
use App\Models\GeneralVoucher;
use App\Models\MoneyExchange;
use App\Models\Party;
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
                'stats' => $this->emptyStats(),
                'businessTimezone' => $businessTimezone,
            ]);
        }

        $stats = [
            'total_banks' => Bank::where('business_id', $businessId)->count(),
            'total_parties' => Party::where('business_id', $businessId)->count(),
            'total_general_vouchers' => GeneralVoucher::where('business_id', $businessId)->count(),
            'total_assets' => Asset::where('business_id', $businessId)->count(),
            'total_party_transfers' => PartyTransfer::forBusiness($businessId)->count(),
            'total_money_exchanges' => MoneyExchange::forBusiness($businessId)->count(),
            'total_bank_transfers' => BankTransfer::forBusiness($businessId)->count(),
            'general_vouchers_amount' => (float) GeneralVoucher::forBusiness($businessId)->sum('amount'),
            'party_transfers_amount' => (float) PartyTransfer::forBusiness($businessId)->sum('debit_amount'),
            'money_exchanges_amount' => (float) MoneyExchange::forBusiness($businessId)->sum('debit_amount'),
            'bank_transfers_amount' => (float) BankTransfer::forBusiness($businessId)->sum('amount'),
        ];

        return view('dashboard', compact('stats', 'businessTimezone'));
    }

    private function emptyStats(): array
    {
        return [
            'total_banks' => 0,
            'total_parties' => 0,
            'total_general_vouchers' => 0,
            'total_assets' => 0,
            'total_party_transfers' => 0,
            'total_money_exchanges' => 0,
            'total_bank_transfers' => 0,
            'general_vouchers_amount' => 0,
            'party_transfers_amount' => 0,
            'money_exchanges_amount' => 0,
            'bank_transfers_amount' => 0,
        ];
    }
}

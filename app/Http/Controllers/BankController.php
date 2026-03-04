<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankRequest;
use App\Models\Bank;
use App\Models\BankLedger;
use App\Models\BankType;
use App\Models\Business;
use App\Models\Currency;
use App\Models\BankTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    /**
     * Display the banks dashboard.
     */
    public function dashboard()
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $business = Business::findOrFail($businessId);

        $totalBanks = Bank::forBusiness($businessId)->count();
        $activeBanks = Bank::forBusiness($businessId)->active()->count();
        $totalTransfers = BankTransfer::where('business_id', $businessId)->count();

        $currenciesInUse = (int) Bank::forBusiness($businessId)
            ->selectRaw('COUNT(DISTINCT currency_id) as c')
            ->value('c');

        $totalBalance = (float) BankLedger::query()
            ->join('banks as b', 'b.bank_id', '=', 'bank_ledger.bank_id')
            ->where('b.business_id', $businessId)
            ->selectRaw('COALESCE(SUM(bank_ledger.deposit_amount), 0) - COALESCE(SUM(bank_ledger.withdrawal_amount), 0) as bank_balance')
            ->value('bank_balance');

        $recentBanks = Bank::forBusiness($businessId)
            ->with(['currency', 'bankType'])
            ->orderByDesc('bank_id')
            ->limit(6)
            ->get();

        $recentTransfers = BankTransfer::with(['fromBank.currency', 'toBank.currency'])
            ->where('business_id', $businessId)
            ->orderByDesc('date_added')
            ->orderByDesc('bank_transfer_id')
            ->limit(5)
            ->get();

        return view('banks.dashboard', compact(
            'business',
            'totalBanks',
            'activeBanks',
            'totalTransfers',
            'currenciesInUse',
            'totalBalance',
            'recentBanks',
            'recentTransfers'
        ));
    }

    public function index(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $banks = Bank::forBusiness($businessId)
            ->with(['currency', 'bankType'])
            ->orderBy('bank_type_id')
            ->orderBy('currency_id')
            ->get();

        return view('banks.index', compact('banks'));
    }

    public function create()
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $currencies = Currency::where('status', 1)->orderBy('currency')->get();
        $bankTypes = BankType::orderBy('bank_type')->get();

        return view('banks.create', compact('currencies', 'bankTypes'));
    }

    public function store(BankRequest $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        DB::beginTransaction();
        try {
            $bank = Bank::create([
                'business_id' => $businessId,
                'bank_name' => strtoupper($request->bank_name),
                'currency_id' => $request->currency_id,
                'account_number' => $request->account_number,
                'bank_type_id' => $request->bank_type_id,
                'opening_balance' => $request->opening_balance ?? 0,
                'status' => $request->status ?? 1,
                'user_id' => auth()->id(),
            ]);

            BankLedger::create([
                'bank_id' => $bank->bank_id,
                'voucher_id' => $bank->bank_id,
                'voucher_type' => 'Opening Balance',
                'deposit_amount' => $request->opening_balance ?? 0,
                'withdrawal_amount' => 0,
                'date_added' => now()->toDateString(),
                'user_id' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('banks.index')->with('success', 'Bank has been added.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to add bank: ' . $e->getMessage());
        }
    }

    public function balance(Bank $bank)
    {
        $businessId = session('active_business');
        if (!$businessId || $bank->business_id != $businessId) {
            return response()->json(['balance' => 0], 403);
        }
        $balance = BankLedger::where('bank_id', $bank->bank_id)
            ->selectRaw('COALESCE(SUM(deposit_amount), 0) - COALESCE(SUM(withdrawal_amount), 0) as balance')
            ->value('balance');
        return response()->json(['balance' => (float) $balance]);
    }

    public function edit(Bank $bank)
    {
        $businessId = session('active_business');
        if (!$businessId || $bank->business_id != $businessId) {
            abort(403, 'Unauthorized access to this bank.');
        }

        $currencies = Currency::where('status', 1)->orderBy('currency')->get();
        $bankTypes = BankType::orderBy('bank_type')->get();

        return view('banks.edit', compact('bank', 'currencies', 'bankTypes'));
    }

    public function update(BankRequest $request, Bank $bank)
    {
        $businessId = session('active_business');
        if ($bank->business_id != $businessId) {
            abort(403, 'Unauthorized access to this bank.');
        }

        DB::beginTransaction();
        try {
            $bank->update([
                'bank_name' => strtoupper($request->bank_name),
                'currency_id' => $request->currency_id,
                'account_number' => $request->account_number,
                'bank_type_id' => $request->bank_type_id,
                'opening_balance' => $request->opening_balance ?? 0,
                'status' => $request->status ?? 1,
                'user_id' => auth()->id(),
            ]);

            BankLedger::where('bank_id', $bank->bank_id)
                ->where('voucher_type', 'Opening Balance')
                ->where('voucher_id', $bank->bank_id)
                ->update([
                    'deposit_amount' => $request->opening_balance ?? 0,
                ]);

            DB::commit();
            return redirect()->route('banks.index')->with('success', 'Bank has been updated.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to update bank: ' . $e->getMessage());
        }
    }

    public function ledger(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->endOfMonth()->format('Y-m-d');
        $bankId = $request->bank_id;

        $banks = Bank::forBusiness($businessId)->active()->orderBy('bank_type_id')->orderBy('bank_name')->get();

        $bankLedger = collect();
        $fields = null;
        $previousBalance = 0;

        $ledgerWithBalance = collect();
        if ($bankId) {
            $bank = Bank::forBusiness($businessId)->find($bankId);
            if ($bank) {
                $fields = $bank->load(['currency', 'bankType']);
                $bankLedger = BankLedger::where('bank_id', $bankId)
                    ->where('date_added', '>=', $dateFrom)
                    ->where('date_added', '<=', $dateTo)
                    ->orderBy('date_added')
                    ->orderBy('bank_ledger_id')
                    ->get();

                $previousBalance = (float) BankLedger::where('bank_id', $bankId)
                    ->where('date_added', '<', $dateFrom)
                    ->selectRaw('COALESCE(SUM(deposit_amount), 0) - COALESCE(SUM(withdrawal_amount), 0) as balance')
                    ->value('balance');

                $running = $previousBalance;
                foreach ($bankLedger as $row) {
                    $running += (float) $row->deposit_amount - (float) $row->withdrawal_amount;
                    $ledgerWithBalance->push((object) [
                        'date_added' => $row->date_added,
                        'voucher_type' => $row->voucher_type,
                        'details' => $row->details,
                        'deposit_amount' => $row->deposit_amount,
                        'withdrawal_amount' => $row->withdrawal_amount,
                        'running_balance' => $running,
                    ]);
                }
            }
        }

        $queryArray = [
            'bank_id' => $bankId,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];

        $business = Business::find($businessId);
        $currencySymbol = $fields?->currency?->currency_symbol ?? '';

        return view('banks.ledger', compact(
            'banks',
            'ledgerWithBalance',
            'fields',
            'previousBalance',
            'queryArray',
            'dateFrom',
            'dateTo',
            'business',
            'currencySymbol'
        ));
    }

    public function bankBalances(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $dateSearch = $request->filled('date_search') ? $request->date_search : now()->format('Y-m-d');

        $bankBalances = BankLedger::query()
            ->join('banks as p', 'p.bank_id', '=', 'bank_ledger.bank_id')
            ->join('currency as c', 'c.currency_id', '=', 'p.currency_id')
            ->where('p.business_id', $businessId)
            ->where('bank_ledger.date_added', '<=', $dateSearch)
            ->selectRaw('p.bank_id, p.bank_name, c.currency, c.currency_symbol, (COALESCE(SUM(bank_ledger.deposit_amount), 0) - COALESCE(SUM(bank_ledger.withdrawal_amount), 0)) as bank_balance')
            ->groupBy('p.bank_id', 'p.bank_name', 'c.currency', 'c.currency_symbol', 'p.bank_type_id', 'p.currency_id')
            ->orderBy('p.bank_type_id')
            ->orderBy('p.currency_id')
            ->get();

        $business = Business::find($businessId);
        return view('banks.bank-balances', compact('bankBalances', 'dateSearch', 'business'));
    }

    public function currencyBalances(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $dateSearch = $request->filled('date_search') ? $request->date_search : now()->format('Y-m-d');

        $currencyBalances = BankLedger::query()
            ->join('banks as p', 'p.bank_id', '=', 'bank_ledger.bank_id')
            ->join('currency as c', 'c.currency_id', '=', 'p.currency_id')
            ->where('p.business_id', $businessId)
            ->where('bank_ledger.date_added', '<=', $dateSearch)
            ->selectRaw('c.currency, c.currency_id, c.currency_symbol, (COALESCE(SUM(bank_ledger.deposit_amount), 0) - COALESCE(SUM(bank_ledger.withdrawal_amount), 0)) as currency_balance')
            ->groupBy('c.currency_id', 'c.currency', 'c.currency_symbol')
            ->orderBy('c.currency_id')
            ->get();

        $business = Business::find($businessId);
        return view('banks.currency-balances', compact('currencyBalances', 'dateSearch', 'business'));
    }
}

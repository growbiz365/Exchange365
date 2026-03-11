<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartyRequest;
use App\Models\Business;
use App\Models\Currency;
use App\Models\Party;
use App\Models\PartyLedger;
use App\Models\PartyOpeningBalance;
use App\Models\PartyTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartyController extends Controller
{
    /**
     * Display the parties dashboard.
     */
    public function dashboard()
    {
        $business = Business::findOrFail(session('active_business'));

        $partyIds = Party::forBusiness($business->id)->pluck('party_id');

        $totalParties = $partyIds->count();
        $totalTransfers = PartyTransfer::where('business_id', $business->id)->count();
        $partiesWithBalance = Party::forBusiness($business->id)->whereHas('openingBalances')->count();
        $currenciesInUse = (int) PartyOpeningBalance::whereIn('party_id', $partyIds)->selectRaw('COUNT(DISTINCT currency_id) as c')->value('c');

        $recentParties = Party::forBusiness($business->id)
            ->with(['openingBalances.currency'])
            ->orderByDesc('party_id')
            ->limit(8)
            ->get();

        $recentTransfers = PartyTransfer::with(['creditParty', 'debitParty', 'creditCurrency', 'debitCurrency'])
            ->where('business_id', $business->id)
            ->orderByDesc('date_added')
            ->orderByDesc('party_transfer_id')
            ->limit(5)
            ->get();

        return view('parties.dashboard', compact(
            'business',
            'totalParties',
            'totalTransfers',
            'partiesWithBalance',
            'currenciesInUse',
            'recentParties',
            'recentTransfers'
        ));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $business = Business::findOrFail(session('active_business'));
        
        $query = Party::with(['openingBalances.currency'])
            ->forBusiness($business->id);

        // Search filters
        if ($request->filled('party_id')) {
            $query->where('party_id', $request->party_id);
        }

        if ($request->filled('party_name')) {
            $query->where('party_name', 'like', '%' . $request->party_name . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $parties = $query->orderBy('party_id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('parties.index', compact('parties', 'business'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $business = Business::findOrFail(session('active_business'));
        $currencies = Currency::where('status', 1)->get();

        return view('parties.create', compact('business', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PartyRequest $request)
    {
        $business = Business::findOrFail(session('active_business'));

        try {
            DB::beginTransaction();

            // Create party
            $party = Party::create([
                'business_id' => $business->id,
                'party_name' => strtoupper($request->party_name),
                'contact_no' => $request->contact_no,
                'party_type' => $request->party_type,
                'status' => $request->status ?? 1,
                'opening_date' => $request->opening_date,
                'user_id' => Auth::id(),
            ]);

            // Process opening balances
            if ($request->has('opening_balances')) {
                foreach ($request->opening_balances as $balance) {
                    if (!empty($balance['opening_balance']) && $balance['opening_balance'] > 0) {
                        // Create opening balance record
                        PartyOpeningBalance::create([
                            'party_id' => $party->party_id,
                            'currency_id' => $balance['currency_id'],
                            'entry_type' => $balance['entry_type'],
                            'opening_balance' => $balance['opening_balance'],
                        ]);

                        // Create ledger entry
                        PartyLedger::create([
                            'business_id' => $business->id,
                            'party_id' => $party->party_id,
                            'currency_id' => $balance['currency_id'],
                            'voucher_id' => $party->party_id,
                            'voucher_type' => 'Opening Balance',
                            'credit_amount' => $balance['entry_type'] == 1 ? $balance['opening_balance'] : 0,
                            'debit_amount' => $balance['entry_type'] == 2 ? $balance['opening_balance'] : 0,
                            'date_added' => $request->opening_date,
                            'details' => 'Opening Balance',
                            'user_id' => Auth::id(),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('parties.index')
                ->with('success', 'Party has been created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create party. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Party $party)
    {
        $business = Business::findOrFail(session('active_business'));
        
        // Ensure party belongs to current business
        if ($party->business_id != $business->id) {
            abort(403, 'Unauthorized access.');
        }

        $party->load(['openingBalances.currency']);
        
        // Get currency balances
        $currencyBalances = $party->getCurrencyBalances();

        return view('parties.show', compact('party', 'business', 'currencyBalances'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Party $party)
    {
        $business = Business::findOrFail(session('active_business'));
        
        // Ensure party belongs to current business
        if ($party->business_id != $business->id) {
            abort(403, 'Unauthorized access.');
        }

        $currencies = Currency::where('status', 1)->get();
        $party->load('openingBalances.currency');

        return view('parties.edit', compact('party', 'business', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PartyRequest $request, Party $party)
    {
        $business = Business::findOrFail(session('active_business'));
        
        // Ensure party belongs to current business
        if ($party->business_id != $business->id) {
            abort(403, 'Unauthorized access.');
        }

        try {
            DB::beginTransaction();

            // Update party
            $party->update([
                'party_name' => strtoupper($request->party_name),
                'contact_no' => $request->contact_no,
                'party_type' => $request->party_type,
                'status' => $request->status ?? 1,
                'opening_date' => $request->opening_date,
            ]);

            // Delete old opening balances and ledger entries
            $party->openingBalances()->delete();
            PartyLedger::where('party_id', $party->party_id)
                ->where('voucher_type', 'Opening Balance')
                ->where('voucher_id', $party->party_id)
                ->delete();

            // Process new opening balances
            if ($request->has('opening_balances')) {
                foreach ($request->opening_balances as $balance) {
                    if (!empty($balance['opening_balance']) && $balance['opening_balance'] > 0) {
                        // Create opening balance record
                        PartyOpeningBalance::create([
                            'party_id' => $party->party_id,
                            'currency_id' => $balance['currency_id'],
                            'entry_type' => $balance['entry_type'],
                            'opening_balance' => $balance['opening_balance'],
                        ]);

                        // Create ledger entry
                        PartyLedger::create([
                            'business_id' => $business->id,
                            'party_id' => $party->party_id,
                            'currency_id' => $balance['currency_id'],
                            'voucher_id' => $party->party_id,
                            'voucher_type' => 'Opening Balance',
                            'credit_amount' => $balance['entry_type'] == 1 ? $balance['opening_balance'] : 0,
                            'debit_amount' => $balance['entry_type'] == 2 ? $balance['opening_balance'] : 0,
                            'date_added' => $request->opening_date,
                            'details' => 'Opening Balance',
                            'user_id' => Auth::id(),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('parties.index')
                ->with('success', 'Party has been updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update party. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Party $party)
    {
        $business = Business::findOrFail(session('active_business'));
        
        // Ensure party belongs to current business
        if ($party->business_id != $business->id) {
            abort(403, 'Unauthorized access.');
        }

        // Check if party has transactions
        if ($party->hasTransactions()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete party with existing transactions.');
        }

        try {
            DB::beginTransaction();

            // Delete will cascade to opening balances and ledger entries
            $party->delete();

            DB::commit();

            return redirect()
                ->route('parties.index')
                ->with('success', 'Party has been deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Failed to delete party. ' . $e->getMessage());
        }
    }

    /**
     * Display party ledger.
     */
    public function ledger(Request $request)
    {
        $business = Business::findOrFail(session('active_business'));

        $parties = Party::forBusiness($business->id)
            ->active()
            ->orderBy('party_name')
            ->get(['party_id', 'party_name']);

        $currencies = Currency::where('status', 1)->orderBy('currency')->get();
        $defaultCurrency = Currency::getDefault();

        $ledgerEntries = null;
        $partyDetails = null;
        $previousBalance = 0;
        $currencySymbol = '';

        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->endOfMonth()->format('Y-m-d');
        $partyId = $request->party_id;
        $currencyId = $request->filled('currency_id')
            ? $request->currency_id
            : ($defaultCurrency->currency_id ?? null);

        if ($request->filled('party_id') && $request->filled('currency_id')) {
            $partyDetails = Party::find($partyId);
            if ($partyDetails && $partyDetails->business_id != $business->id) {
                $partyDetails = null;
            }

            if ($partyDetails) {
                // Previous balance (before date_from) for this party + currency
                $previousBalanceData = PartyLedger::where('party_id', $partyId)
                    ->where('currency_id', $currencyId)
                    ->where('date_added', '<', $dateFrom)
                    ->selectRaw('SUM(credit_amount) - SUM(debit_amount) as balance')
                    ->first();

                $previousBalance = $previousBalanceData->balance ?? 0;

                // Ledger entries in date range
                $ledgerEntries = PartyLedger::with(['currency'])
                    ->where('party_id', $partyId)
                    ->where('currency_id', $currencyId)
                    ->whereBetween('date_added', [$dateFrom, $dateTo])
                    ->orderBy('date_added', 'asc')
                    ->orderBy('party_ledger_id', 'asc')
                    ->get();

                $currency = Currency::where('currency_id', $currencyId)->first();
                $currencySymbol = $currency ? ($currency->currency_symbol ?? $currency->currency) : '';
            }
        }

        $dataDuration = 'From ' . \Carbon\Carbon::parse($dateFrom)->format('d M Y') . ' To ' . \Carbon\Carbon::parse($dateTo)->format('d M Y');

        return view('parties.ledger', compact(
            'business',
            'parties',
            'currencies',
            'currencyId',
            'ledgerEntries',
            'partyDetails',
            'previousBalance',
            'dateFrom',
            'dateTo',
            'currencySymbol',
            'dataDuration'
        ));
    }

    /**
     * Display party balances by currency.
     */
    public function balances(Request $request)
    {
        $business = Business::findOrFail(session('active_business'));
        
        $currencies = Currency::where('status', 1)->get();
        $defaultCurrency = Currency::getDefault();
        $dateSearch = $request->date_search ?? now()->format('Y-m-d');
        $currencyId = $request->filled('currency_id')
            ? $request->currency_id
            : ($defaultCurrency->currency_id ?? null);

        $partyBalances = null;

        if ($currencyId) {
            $partyBalances = Party::forBusiness($business->id)
                ->where('party_type', 1) // Only regular parties, not expense
                ->whereHas('ledgerEntries', function ($query) use ($currencyId, $dateSearch) {
                    $query->where('currency_id', $currencyId)
                        ->where('date_added', '<=', $dateSearch);
                })
                ->with(['ledgerEntries' => function ($query) use ($currencyId, $dateSearch) {
                    $query->where('currency_id', $currencyId)
                        ->where('date_added', '<=', $dateSearch)
                        ->selectRaw('
                            party_id,
                            SUM(credit_amount) - SUM(debit_amount) as balance
                        ')
                        ->groupBy('party_id');
                }])
                ->get()
                ->map(function ($party) {
                    $balance = $party->ledgerEntries->first()->balance ?? 0;
                    return [
                        'party_id' => $party->party_id,
                        'party_name' => $party->party_name,
                        'balance' => $balance,
                    ];
                })
                ->filter(function ($party) {
                    return $party['balance'] != 0;
                });
        }

        return view('parties.balances', compact(
            'business',
            'currencies',
            'partyBalances',
            'dateSearch',
            'currencyId'
        ));
    }

    /**
     * Get current balance for a party and currency (AJAX).
     */
    public function balance(Party $party, Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId || $party->business_id != $businessId) {
            return response()->json(['balance' => 0], 403);
        }

        $currencyId = (int) $request->query('currency_id', 0);
        if (!$currencyId) {
            return response()->json(['balance' => 0]);
        }

        $balance = (float) PartyLedger::where('party_id', $party->party_id)
            ->where('currency_id', $currencyId)
            ->selectRaw('COALESCE(SUM(credit_amount), 0) - COALESCE(SUM(debit_amount), 0) as balance')
            ->value('balance');

        return response()->json(['balance' => $balance]);
    }

    /**
     * Display party currency breakdown.
     */
    public function currencyBreakdown(Request $request)
    {
        $business = Business::findOrFail(session('active_business'));
        
        $parties = Party::forBusiness($business->id)
            ->active()
            ->orderBy('party_name')
            ->get(['party_id', 'party_name']);

        $dateSearch = $request->date_search ?? now()->format('Y-m-d');
        $partyId = $request->party_id;

        $partyDetails = null;
        $currencyBalances = null;

        if ($partyId) {
            $partyDetails = Party::findOrFail($partyId);
            
            $currencyBalances = PartyLedger::with('currency:currency_id,currency,currency_symbol')
                ->where('party_id', $partyId)
                ->where('date_added', '<=', $dateSearch)
                ->selectRaw('
                    currency_id,
                    SUM(credit_amount) - SUM(debit_amount) as balance
                ')
                ->groupBy('currency_id')
                ->having('balance', '!=', 0)
                ->get();
        }

        return view('parties.currency-breakdown', compact(
            'business',
            'parties',
            'partyDetails',
            'currencyBalances',
            'dateSearch',
            'partyId'
        ));
    }
}

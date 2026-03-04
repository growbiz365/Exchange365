<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Http\Requests\AssetSaleRequest;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Bank;
use App\Models\BankLedger;
use App\Models\Business;
use App\Models\Currency;
use App\Models\Party;
use App\Models\PartyLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    /**
     * Validate that selected purchase bank has sufficient balance
     * for the asset cost (only when purchase_transaction_type == 2).
     *
     * For updates: if the asset was previously purchased from the same bank,
     * we add back the existing withdrawal for this asset to avoid false failures.
     */
    protected function validateSufficientPurchaseBankBalance(
        int $businessId,
        int $purchaseTransactionType,
        ?int $purchaseBankId,
        float $costAmount,
        ?Asset $existingAsset = null
    ): ?\Illuminate\Http\RedirectResponse {
        if ($purchaseTransactionType !== 2) {
            return null;
        }

        if (!$purchaseBankId || $costAmount <= 0) {
            return null;
        }

        $bank = Bank::forBusiness($businessId)->active()->where('bank_id', $purchaseBankId)->first();
        if (!$bank) {
            return back()->withInput()->with('error', 'Selected purchase bank is invalid.');
        }

        $balance = (float) BankLedger::where('bank_id', $purchaseBankId)
            ->selectRaw('COALESCE(SUM(deposit_amount), 0) - COALESCE(SUM(withdrawal_amount), 0) as balance')
            ->value('balance');

        // If editing and the previous purchase bank is the same, exclude old withdrawal by adding it back.
        if (
            $existingAsset &&
            (int) $existingAsset->purchase_transaction_type === 2 &&
            (int) $existingAsset->purchase_bank_id === (int) $purchaseBankId
        ) {
            $oldWithdrawal = (float) BankLedger::where('bank_id', $purchaseBankId)
                ->where('voucher_id', $existingAsset->asset_id)
                ->where('voucher_type', Asset::VOUCHER_TYPE)
                ->sum('withdrawal_amount');
            $balance += $oldWithdrawal;
        }

        if ($balance < $costAmount) {
            return back()->withInput()->with(
                'error',
                'Insufficient bank balance for this asset cost. Available: ' .
                    number_format($balance, 2) .
                    ', Required: ' .
                    number_format($costAmount, 2)
            );
        }

        return null;
    }

    public function index(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $query = Asset::with(['category'])
            ->forBusiness($businessId);

        if ($request->filled('asset_name')) {
            $query->where('asset_name', 'like', '%' . $request->asset_name . '%');
        }
        if ($request->filled('date_from')) {
            $query->where('date_added', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date_added', '<=', $request->date_to);
        }

        $assets = $query->orderByDesc('asset_id')->paginate(15)->withQueryString();

        return view('assets.index', compact('assets'));
    }

    /**
     * Assets dashboard with summary stats and quick links.
     */
    public function dashboard(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $totalAssets = Asset::forBusiness($businessId)->count();
        $activeAssets = Asset::forBusiness($businessId)->active()->count();
        $soldAssets = Asset::forBusiness($businessId)->where('asset_status', Asset::STATUS_SOLD)->count();

        $totalCost = Asset::forBusiness($businessId)->sum('cost_amount');
        $totalSaleValue = Asset::forBusiness($businessId)
            ->where('asset_status', Asset::STATUS_SOLD)
            ->sum('sale_amount');

        $categoriesCount = AssetCategory::forBusiness($businessId)->active()->count();

        $recentAssets = Asset::with('category')
            ->forBusiness($businessId)
            ->orderByDesc('asset_id')
            ->limit(7)
            ->get();

        return view('assets.dashboard', compact(
            'totalAssets',
            'activeAssets',
            'soldAssets',
            'totalCost',
            'totalSaleValue',
            'categoriesCount',
            'recentAssets'
        ));
    }

    public function create()
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $categories = AssetCategory::forBusiness($businessId)->active()->orderBy('asset_category')->get();
        // Show all active banks for this business (not just PKR) so the dropdown is always populated.
        $banks = Bank::forBusiness($businessId)->active()->orderBy('bank_name')->get();
        $parties = Party::forBusiness($businessId)->active()->orderBy('party_name')->get();
        $business = Business::find($businessId);
        $defaultCurrencyId = ($business && $business->currency_id) ? $business->currency_id : (Currency::orderBy('currency_id')->value('currency_id') ?? 1);

        return view('assets.create', compact('categories', 'banks', 'parties', 'defaultCurrencyId'));
    }

    public function store(AssetRequest $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $balanceError = $this->validateSufficientPurchaseBankBalance(
            (int) $businessId,
            (int) $request->purchase_transaction_type,
            $request->purchase_bank_id ? (int) $request->purchase_bank_id : null,
            (float) $request->cost_amount,
            null
        );
        if ($balanceError) {
            return $balanceError;
        }

        DB::beginTransaction();
        try {
            $asset = Asset::create([
                'business_id' => $businessId,
                'asset_category_id' => $request->asset_category_id,
                'date_added' => $request->date_added,
                'purchase_transaction_type' => (int) $request->purchase_transaction_type,
                'asset_name' => $request->asset_name,
                'cost_amount' => $request->cost_amount,
                'purchase_bank_id' => $request->purchase_bank_id,
                'purchase_party_id' => $request->purchase_party_id,
                'purchase_details' => $request->purchase_details,
                'asset_status' => Asset::STATUS_ACTIVE,
                'user_id' => Auth::id(),
            ]);

            $this->syncPurchaseLedgers($asset);

            DB::commit();
            return redirect()->route('assets.index')
                ->with('success', 'Asset has been added.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create asset: ' . $e->getMessage());
        }
    }

    public function edit(Asset $asset)
    {
        $businessId = session('active_business');
        if (!$businessId || $asset->business_id != $businessId) {
            abort(403, 'Unauthorized access to this asset.');
        }

        $categories = AssetCategory::forBusiness($businessId)->active()->orderBy('asset_category')->get();
        // Show all active banks for this business (not just PKR) so the dropdown is always populated.
        $banks = Bank::forBusiness($businessId)->active()->orderBy('bank_name')->get();
        $parties = Party::forBusiness($businessId)->active()->orderBy('party_name')->get();
        $business = Business::find($businessId);
        $defaultCurrencyId = ($business && $business->currency_id) ? $business->currency_id : (Currency::orderBy('currency_id')->value('currency_id') ?? 1);

        return view('assets.edit', compact('asset', 'categories', 'banks', 'parties', 'defaultCurrencyId'));
    }

    public function update(AssetRequest $request, Asset $asset)
    {
        $businessId = session('active_business');
        if (!$businessId || $asset->business_id != $businessId) {
            abort(403, 'Unauthorized access to this asset.');
        }

        $balanceError = $this->validateSufficientPurchaseBankBalance(
            (int) $businessId,
            (int) $request->purchase_transaction_type,
            $request->purchase_bank_id ? (int) $request->purchase_bank_id : null,
            (float) $request->cost_amount,
            $asset
        );
        if ($balanceError) {
            return $balanceError;
        }

        DB::beginTransaction();
        try {
            $asset->update([
                'asset_category_id' => $request->asset_category_id,
                'date_added' => $request->date_added,
                'purchase_transaction_type' => (int) $request->purchase_transaction_type,
                'asset_name' => $request->asset_name,
                'cost_amount' => $request->cost_amount,
                'purchase_bank_id' => $request->purchase_bank_id,
                'purchase_party_id' => $request->purchase_party_id,
                'purchase_details' => $request->purchase_details,
            ]);

            BankLedger::where('voucher_id', $asset->asset_id)
                ->where('voucher_type', Asset::VOUCHER_TYPE)
                ->delete();
            PartyLedger::where('voucher_id', $asset->asset_id)
                ->where('voucher_type', Asset::VOUCHER_TYPE)
                ->delete();

            $this->syncPurchaseLedgers($asset->fresh());

            DB::commit();
            return redirect()->route('assets.index')
                ->with('success', 'Asset has been updated.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update asset: ' . $e->getMessage());
        }
    }

    public function destroy(Asset $asset)
    {
        $businessId = session('active_business');
        if (!$businessId || $asset->business_id != $businessId) {
            abort(403, 'Unauthorized access to this asset.');
        }

        DB::beginTransaction();
        try {
            BankLedger::where('voucher_id', $asset->asset_id)
                ->where('voucher_type', Asset::VOUCHER_TYPE)
                ->delete();
            PartyLedger::where('voucher_id', $asset->asset_id)
                ->where('voucher_type', Asset::VOUCHER_TYPE)
                ->delete();

            $asset->delete();

            DB::commit();
            return redirect()->route('assets.index')
                ->with('success', 'Asset has been deleted.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete asset: ' . $e->getMessage());
        }
    }

    public function sellForm(Asset $asset)
    {
        $businessId = session('active_business');
        if (!$businessId || $asset->business_id != $businessId) {
            abort(403, 'Unauthorized access to this asset.');
        }

        $banks = Bank::forBusiness($businessId)->active()->orderBy('bank_name')->get();
        $parties = Party::forBusiness($businessId)->active()->orderBy('party_name')->get();
        $business = Business::find($businessId);
        $defaultCurrencyId = ($business && $business->currency_id) ? $business->currency_id : (Currency::orderBy('currency_id')->value('currency_id') ?? 1);

        return view('assets.sell', compact('asset', 'banks', 'parties', 'defaultCurrencyId'));
    }

    public function sell(AssetSaleRequest $request, Asset $asset)
    {
        $businessId = session('active_business');
        if (!$businessId || $asset->business_id != $businessId) {
            abort(403, 'Unauthorized access to this asset.');
        }

        DB::beginTransaction();
        try {
            $asset->update([
                'sale_date' => $request->sale_date,
                'sale_transaction_type' => (int) $request->sale_transaction_type,
                'sale_amount' => $request->sale_amount,
                'sale_bank_id' => $request->sale_bank_id,
                'sale_party_id' => $request->sale_party_id,
                'sale_details' => $request->sale_details,
                'asset_status' => Asset::STATUS_SOLD,
            ]);

            // Remove previous sale ledgers if any
            BankLedger::where('voucher_id', $asset->asset_id)
                ->where('voucher_type', Asset::VOUCHER_TYPE)
                ->where('deposit_amount', '>', 0)
                ->delete();
            PartyLedger::where('voucher_id', $asset->asset_id)
                ->where('voucher_type', Asset::VOUCHER_TYPE)
                ->where('debit_amount', '>', 0)
                ->delete();

            $this->syncSaleLedgers($asset->fresh());

            DB::commit();
            return redirect()->route('assets.index')
                ->with('success', 'Asset has been marked as sold.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to sell asset: ' . $e->getMessage());
        }
    }

    public function show(Asset $asset)
    {
        $businessId = session('active_business');
        if (!$businessId || $asset->business_id != $businessId) {
            abort(403, 'Unauthorized access to this asset.');
        }

        $asset->load(['category', 'purchaseBank', 'purchaseParty', 'saleBank', 'saleParty', 'user']);

        return view('assets.show', compact('asset'));
    }

    public function print(Asset $asset)
    {
        $businessId = session('active_business');
        if (!$businessId || $asset->business_id != $businessId) {
            abort(403, 'Unauthorized access to this asset.');
        }

        $business = Business::find($businessId);
        $asset->load(['category', 'purchaseBank', 'purchaseParty', 'saleBank', 'saleParty', 'user']);

        return view('assets.print', compact('asset', 'business'));
    }

    protected function syncPurchaseLedgers(Asset $asset): void
    {
        $details = trim((string) $asset->purchase_details);
        $baseDetails = 'Purchase: ' . $asset->asset_name;
        $text = trim($baseDetails . ' ' . $details);

        if ((int) $asset->purchase_transaction_type === 2 && $asset->purchase_bank_id) {
            BankLedger::create([
                'bank_id' => $asset->purchase_bank_id,
                'voucher_id' => $asset->asset_id,
                'voucher_type' => Asset::VOUCHER_TYPE,
                'deposit_amount' => 0,
                'withdrawal_amount' => $asset->cost_amount,
                'date_added' => $asset->date_added,
                'details' => $text,
                'user_id' => $asset->user_id,
            ]);
        }

        if ((int) $asset->purchase_transaction_type === 3 && $asset->purchase_party_id) {
            PartyLedger::create([
                'party_id' => $asset->purchase_party_id,
                'currency_id' => 1, // PKR
                'voucher_id' => $asset->asset_id,
                'voucher_type' => Asset::VOUCHER_TYPE,
                'credit_amount' => $asset->cost_amount,
                'debit_amount' => 0,
                'date_added' => $asset->date_added,
                'details' => $text,
                'user_id' => $asset->user_id,
            ]);
        }
    }

    protected function syncSaleLedgers(Asset $asset): void
    {
        if (!$asset->sale_transaction_type || !$asset->sale_amount) {
            return;
        }

        $details = trim((string) $asset->sale_details);
        $baseDetails = 'Sale: ' . $asset->asset_name;
        $text = trim($baseDetails . ' ' . $details);

        if ((int) $asset->sale_transaction_type === 2 && $asset->sale_bank_id) {
            BankLedger::create([
                'bank_id' => $asset->sale_bank_id,
                'voucher_id' => $asset->asset_id,
                'voucher_type' => Asset::VOUCHER_TYPE,
                'deposit_amount' => $asset->sale_amount,
                'withdrawal_amount' => 0,
                'date_added' => $asset->sale_date,
                'details' => $text,
                'user_id' => $asset->user_id,
            ]);
        }

        if ((int) $asset->sale_transaction_type === 3 && $asset->sale_party_id) {
            PartyLedger::create([
                'party_id' => $asset->sale_party_id,
                'currency_id' => 1, // PKR
                'voucher_id' => $asset->asset_id,
                'voucher_type' => Asset::VOUCHER_TYPE,
                'credit_amount' => 0,
                'debit_amount' => $asset->sale_amount,
                'date_added' => $asset->sale_date,
                'details' => $text,
                'user_id' => $asset->user_id,
            ]);
        }
    }
}


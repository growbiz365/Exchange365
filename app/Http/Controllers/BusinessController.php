<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Country;
use App\Models\Timezone;
use App\Models\City;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BusinessController extends Controller
{
    /**
     * Display a listing of the businesses.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $businesses = Business::with(['country', 'timezone', 'currency']);

        // Only super-admin sees all businesses
        if (!$user->hasRole('Super Admin')) {
            $businesses = $businesses->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $businesses = $businesses->where(function ($query) use ($search) {
                $query->where('business_name', 'like', "%$search%")
                    ->orWhere('owner_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhereHas('country', function ($q) use ($search) {
                        $q->where('country_name', 'like', "%$search%");
                    })
                    ->orWhereHas('timezone', function ($q) use ($search) {
                        $q->where('timezone_name', 'like', "%$search%");
                    });
            });
        }

        $businesses = $businesses->paginate(10);
        return view('businesses.index', compact('businesses'));
    }

    /**
     * Show the form for creating a new business.
     */
    public function create()
    {
        $countries = Country::orderBy('country_name')->get();
        $timezones = Timezone::orderBy('timezone_name')->get();
        $currencies = Currency::orderBy('currency')->get();
        $cities = City::orderBy('name')->get();
        $isAdmin = auth()->user()->hasRole('admin');
        return view('businesses.create', compact('countries', 'timezones', 'currencies', 'cities', 'isAdmin'));
    }

    /**
     * Store a newly created business in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            // Only business_name and owner_name are required
            $validated = $request->validate([
                // Business info
                'business_name' => 'required|string|max:255',
                'owner_name' => 'required|string|max:255',
                'cnic' => 'nullable|string|max:15|unique:businesses,cnic',
                'contact_no' => 'nullable|string|max:20',
                'email' => 'nullable|email|unique:businesses,email',
                'address' => 'nullable|string',
                'country_id' => 'nullable|exists:countries,id',
                'timezone_id' => 'nullable|exists:timezones,id',
                'currency_id' => 'nullable|exists:currency,currency_id',
                'date_format' => 'nullable|in:Y-m-d,d/m/Y,m/d/Y,d-m-Y',
                // Store info
                'store_name' => 'nullable|string|max:255',
                'store_license_number' => 'nullable|string|max:100',
                'license_expiry_date' => 'nullable|date',
                'issuing_authority' => 'nullable|string|max:255',
                'store_type' => 'nullable|string|max:100',
                'ntn' => 'nullable|string|max:50',
                'strn' => 'nullable|string|max:50',
                'store_phone' => 'nullable|string|max:20',
                'store_email' => 'nullable|email',
                'store_address' => 'nullable|string',
                'store_city_id' => 'nullable|exists:cities,id',
                'store_country_id' => 'nullable|exists:countries,id',
                'store_postal_code' => 'nullable|string|max:20',
            ]);
        } else {
            // Non-admin: validate only store info, but still require business_name and owner_name
            $validated = $request->validate([
                'business_name' => 'required|string|max:255',
                'owner_name' => 'required|string|max:255',
                'store_name' => 'nullable|string|max:255',
                'store_license_number' => 'nullable|string|max:100',
                'license_expiry_date' => 'nullable|date',
                'issuing_authority' => 'nullable|string|max:255',
                'store_type' => 'nullable|string|max:100',
                'ntn' => 'nullable|string|max:50',
                'strn' => 'nullable|string|max:50',
                'store_phone' => 'nullable|string|max:20',
                'store_email' => 'nullable|email',
                'store_address' => 'nullable|string',
                'store_city_id' => 'nullable|exists:cities,id',
                'store_country_id' => 'nullable|exists:countries,id',
                'store_postal_code' => 'nullable|string|max:20',
            ]);
        }

        $business = Business::create($validated);
        // Optionally assign to current user
        $business->users()->attach(Auth::id());

        return redirect()->route('businesses.index')->with('success', 'Business created successfully.');
    }

    /**
     * Display the specified business.
     */
    public function show(Business $business)
    {
        $business->load(['country', 'timezone', 'currency']);
        $isAdmin = auth()->user()->hasRole('Super Admin');
        return view('businesses.show', compact('business', 'isAdmin'));
    }

    /**
     * Show the form for editing store info (store details, not business profile).
     */
    public function editStoreInfo(Business $business)
    {
        $countries = Country::orderBy('country_name')->get();
        $cities = City::orderBy('name')->get();
        return view('businesses.edit', compact('business', 'countries', 'cities'));
    }

    /**
     * Update the store info (store details, not business profile).
     */
    public function updateStoreInfo(Request $request, Business $business)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'license_no' => 'nullable|string|max:100',
            'expiry' => 'nullable|date',
            'issuing_authority' => 'nullable|string|max:255',
            'store_type' => 'nullable|string|max:100',
            'ntn' => 'nullable|string|max:50',
            'strn' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);
        $business->update($validated);
        return redirect()->route('businesses.show', $business)->with('success', 'Store information updated successfully.');
    }

    /**
     * Show the form for editing the specified business.
     */
    public function edit(Business $business)
    {
        $countries = Country::orderBy('country_name')->get();
        $timezones = Timezone::orderBy('timezone_name')->get();
        $currencies = Currency::orderBy('currency')->get();
        $cities = City::orderBy('name')->get();
        $isAdmin = auth()->user()->hasRole('Super Admin');
        return view('businesses.edit', compact('business', 'countries', 'timezones', 'currencies', 'cities', 'isAdmin'));
    }

    /**
     * Update the specified business in storage.
     */
    public function update(Request $request, Business $business)
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            // Only business_name and owner_name are required
            $validated = $request->validate([
                // Business info
                'business_name' => 'required|string|max:255',
                'owner_name' => 'required|string|max:255',
                'cnic' => 'nullable|string|max:15|unique:businesses,cnic,' . $business->id,
                'contact_no' => 'nullable|string|max:20',
                'email' => 'nullable|email|unique:businesses,email,' . $business->id,
                'address' => 'nullable|string',
                'country_id' => 'nullable|exists:countries,id',
                'timezone_id' => 'nullable|exists:timezones,id',
                'currency_id' => 'nullable|exists:currency,currency_id',
                'date_format' => 'nullable|in:Y-m-d,d/m/Y,m/d/Y,d-m-Y',
                // Store info
                'store_name' => 'nullable|string|max:255',
                'store_license_number' => 'nullable|string|max:100',
                'license_expiry_date' => 'nullable|date',
                'issuing_authority' => 'nullable|string|max:255',
                'store_type' => 'nullable|string|max:100',
                'ntn' => 'nullable|string|max:50',
                'strn' => 'nullable|string|max:50',
                'store_phone' => 'nullable|string|max:20',
                'store_email' => 'nullable|email',
                'store_address' => 'nullable|string',
                'store_city_id' => 'nullable|exists:cities,id',
                'store_country_id' => 'nullable|exists:countries,id',
                'store_postal_code' => 'nullable|string|max:20',
            ]);
        } else {
            // Non-admin: validate only store info, but still require business_name and owner_name
            $validated = $request->validate([
                'business_name' => 'required|string|max:255',
                'owner_name' => 'required|string|max:255',
                'store_name' => 'nullable|string|max:255',
                'store_license_number' => 'nullable|string|max:100',
                'license_expiry_date' => 'nullable|date',
                'issuing_authority' => 'nullable|string|max:255',
                'store_type' => 'nullable|string|max:100',
                'ntn' => 'nullable|string|max:50',
                'strn' => 'nullable|string|max:50',
                'store_phone' => 'nullable|string|max:20',
                'store_email' => 'nullable|email',
                'store_address' => 'nullable|string',
                'store_city_id' => 'nullable|exists:cities,id',
                'store_country_id' => 'nullable|exists:countries,id',
                'store_postal_code' => 'nullable|string|max:20',
            ]);
        }
        $business->update($validated);
        return redirect()->route('businesses.index')->with('success', 'Business updated successfully.');
    }

    /**
     * Remove the specified business from storage.
     * This will delete ALL data associated with the business including transactions,
     * inventory, master data, and the business record itself.
     */
    public function destroy(Request $request, Business $business)
    {
        // Only Super Admin can delete businesses
        if (!Auth::user()->hasRole('Super Admin')) {
            return redirect()->back()->with('error', 'You do not have permission to delete businesses.');
        }

        // Validate confirmation
        $request->validate([
            'confirmation_text' => 'required|string',
        ]);

        // Check if confirmation matches business name
        if ($request->confirmation_text !== $business->business_name) {
            return redirect()->back()->with('error', 'Business name confirmation does not match. Deletion cancelled.');
        }

        try {
            DB::beginTransaction();

            $businessId = $business->id;
            $businessName = $business->business_name;

            // Phase 1: Delete Audit & Activity Logs
            \DB::table('activity_logs')->where('business_id', $businessId)->delete();
            
            // Get all purchases for this business to delete their audit logs
            $purchaseIds = \DB::table('purchases')->where('business_id', $businessId)->pluck('id');
            if ($purchaseIds->isNotEmpty()) {
                \DB::table('purchase_audit_logs')->whereIn('purchase_id', $purchaseIds)->delete();
            }

            // Get all sale invoices for this business to delete their audit logs
            $saleInvoiceIds = \DB::table('sale_invoices')->where('business_id', $businessId)->pluck('id');
            if ($saleInvoiceIds->isNotEmpty()) {
                \DB::table('sale_invoice_audit_logs')->whereIn('sale_invoice_id', $saleInvoiceIds)->delete();
            }

            // Get all sale returns for this business to delete their audit logs
            $saleReturnIds = \DB::table('sale_returns')->where('business_id', $businessId)->pluck('id');
            if ($saleReturnIds->isNotEmpty()) {
                \DB::table('sale_return_audit_logs')->whereIn('sale_return_id', $saleReturnIds)->delete();
            }

            // Get all purchase returns for this business to delete their audit logs
            $purchaseReturnIds = \DB::table('purchase_returns')->where('business_id', $businessId)->pluck('id');
            if ($purchaseReturnIds->isNotEmpty()) {
                \DB::table('purchase_return_audit_logs')->whereIn('purchase_return_id', $purchaseReturnIds)->delete();
            }

            // Phase 2: Delete Main Transactions
            \DB::table('sale_invoices')->where('business_id', $businessId)->delete();
            \DB::table('sale_returns')->where('business_id', $businessId)->delete();
            \DB::table('purchases')->where('business_id', $businessId)->delete();
            \DB::table('purchase_returns')->where('business_id', $businessId)->delete();
            \DB::table('quotations')->where('business_id', $businessId)->delete();
            \DB::table('approvals')->where('business_id', $businessId)->delete();

            // Phase 3: Delete Financial Transactions & Attachments
            
            // Expenses
            $expenseIds = \DB::table('expenses')->where('business_id', $businessId)->pluck('id');
            if ($expenseIds->isNotEmpty()) {
                \DB::table('expense_attachments')->whereIn('expense_id', $expenseIds)->delete();
            }
            \DB::table('expenses')->where('business_id', $businessId)->delete();

            // Other incomes
            $otherIncomeIds = \DB::table('other_incomes')->where('business_id', $businessId)->pluck('id');
            if ($otherIncomeIds->isNotEmpty()) {
                \DB::table('other_income_attachments')->whereIn('other_income_id', $otherIncomeIds)->delete();
            }
            \DB::table('other_incomes')->where('business_id', $businessId)->delete();

            // General vouchers
            $generalVoucherIds = \DB::table('general_vouchers')->where('business_id', $businessId)->pluck('id');
            if ($generalVoucherIds->isNotEmpty()) {
                \DB::table('general_voucher_attachments')->whereIn('general_voucher_id', $generalVoucherIds)->delete();
            }
            \DB::table('general_vouchers')->where('business_id', $businessId)->delete();

            // Bank transfers
            $bankTransferIds = \DB::table('bank_transfers')->where('business_id', $businessId)->pluck('id');
            if ($bankTransferIds->isNotEmpty()) {
                \DB::table('bank_transfer_attachments')->whereIn('bank_transfer_id', $bankTransferIds)->delete();
            }
            \DB::table('bank_transfers')->where('business_id', $businessId)->delete();

            // Party transfers
            $partyTransferIds = \DB::table('party_transfers')->where('business_id', $businessId)->pluck('id');
            if ($partyTransferIds->isNotEmpty()) {
                \DB::table('party_transfer_attachments')->whereIn('party_transfer_id', $partyTransferIds)->delete();
            }
            \DB::table('party_transfers')->where('business_id', $businessId)->delete();

            // Journal entries
            \DB::table('journal_entries')->where('business_id', $businessId)->delete();

            // Phase 4: Delete Ledger Entries
            \DB::table('bank_ledger')->where('business_id', $businessId)->delete();
            \DB::table('party_ledgers')->where('business_id', $businessId)->delete();

            // Phase 5: Delete Master Data
            \DB::table('banks')->where('business_id', $businessId)->delete();
            \DB::table('parties')->where('business_id', $businessId)->delete();
            \DB::table('expense_heads')->where('business_id', $businessId)->delete();
            \DB::table('income_heads')->where('business_id', $businessId)->delete();

            // Phase 6: Delete Business-User Relationships
            // The business_user pivot table has cascade delete, but we'll explicitly delete it
            \DB::table('business_user')->where('business_id', $businessId)->delete();

            // Phase 7: Delete the Business Record Itself
        $business->delete();

            // Log the action
            Log::info('Business completely deleted', [
                'business_id' => $businessId,
                'business_name' => $businessName,
                'deleted_by' => Auth::user()->id,
                'deleted_by_name' => Auth::user()->name,
                'timestamp' => now()
            ]);

            DB::commit();

            return redirect()->route('businesses.index')
                ->with('success', 'Business and all associated data have been permanently deleted.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to delete business', [
                'business_id' => $business->id,
                'business_name' => $business->business_name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Failed to delete business: ' . $e->getMessage());
        }
    }

    /**
     * Search for businesses (AJAX, e.g. for comboboxes).
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();
        $businesses = Business::query();
        if (!$user->hasRole('Super Admin')) {
            $businesses->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        if ($query) {
            $businesses->where('business_name', 'like', "%$query%")
                ->orWhere('owner_name', 'like', "%$query%")
                ->orWhere('email', 'like', "%$query%");
        }
        $results = $businesses->limit(10)->get(['id', 'business_name', 'owner_name']);
        return response()->json($results);
    }

    public function setActiveBusiness($businessId)
    {
        $business = Business::findOrFail($businessId);

        if ($business->users->contains(auth()->user())) {
            session(['active_business' => $businessId]);
            session()->save();
            activity('session')
                ->causedBy(auth()->user())
                ->performedOn($business)
                ->withProperties(['business_name' => $business->business_name])
                ->log('Switched active business');
            \Log::info('Active business set in session', [
                'user_id' => auth()->user()->id,
                'business_id' => $businessId,
                'session' => session('active_business')
            ]);
        } else {
            \Log::warning('User does not belong to business', [
                'user_id' => auth()->user()->id,
                'business_id' => $businessId
            ]);
        }

        return redirect()->back();
    }

    /**
     * Suspend a business
     */
    public function suspend(Request $request, Business $business)
    {
        // Only Super Admin can suspend businesses
        if (!Auth::user()->hasRole('Super Admin')) {
            return redirect()->back()->with('error', 'You do not have permission to suspend businesses.');
        }

        $request->validate([
            'suspension_reason' => 'nullable|string|max:1000'
        ]);

        $business->suspend($request->suspension_reason);

        Log::info('Business suspended', [
            'business_id' => $business->id,
            'business_name' => $business->business_name,
            'suspended_by' => Auth::user()->id,
            'reason' => $request->suspension_reason
        ]);

        return redirect()->back()->with('success', 'Business has been suspended successfully.');
    }

    /**
     * Unsuspend a business
     */
    public function unsuspend(Business $business)
    {
        // Only Super Admin can unsuspend businesses
        if (!Auth::user()->hasRole('Super Admin')) {
            return redirect()->back()->with('error', 'You do not have permission to unsuspend businesses.');
        }

        $business->unsuspend();

        Log::info('Business unsuspended', [
            'business_id' => $business->id,
            'business_name' => $business->business_name,
            'unsuspended_by' => Auth::user()->id
        ]);

        return redirect()->back()->with('success', 'Business has been unsuspended successfully.');
    }

    /**
     * Clear all data for a specific business (Complete Clear)
     * This will delete all transactions, inventory, master data but keep the business record
     */
    public function clearAllData(Request $request, Business $business)
    {
        // Only Super Admin can clear business data
        if (!Auth::user()->hasRole('Super Admin')) {
            return redirect()->back()->with('error', 'You do not have permission to clear business data.');
        }

        // Validate confirmation
        $request->validate([
            'confirmation_text' => 'required|string',
        ]);

        // Check if confirmation matches business name
        if ($request->confirmation_text !== $business->business_name) {
            return redirect()->back()->with('error', 'Business name confirmation does not match. Data clear cancelled.');
        }

        try {
            DB::beginTransaction();

            $businessId = $business->id;
            $businessName = $business->business_name;

            // Phase 1: Delete Audit & Activity Logs
            \DB::table('activity_logs')->where('business_id', $businessId)->delete();
            
            // Get all purchases for this business to delete their audit logs
            $purchaseIds = \DB::table('purchases')->where('business_id', $businessId)->pluck('id');
            if ($purchaseIds->isNotEmpty()) {
                \DB::table('purchase_audit_logs')->whereIn('purchase_id', $purchaseIds)->delete();
            }

            // Get all sale invoices for this business to delete their audit logs
            $saleInvoiceIds = \DB::table('sale_invoices')->where('business_id', $businessId)->pluck('id');
            if ($saleInvoiceIds->isNotEmpty()) {
                \DB::table('sale_invoice_audit_logs')->whereIn('sale_invoice_id', $saleInvoiceIds)->delete();
            }

            // Get all sale returns for this business to delete their audit logs
            $saleReturnIds = \DB::table('sale_returns')->where('business_id', $businessId)->pluck('id');
            if ($saleReturnIds->isNotEmpty()) {
                \DB::table('sale_return_audit_logs')->whereIn('sale_return_id', $saleReturnIds)->delete();
            }

            // Get all purchase returns for this business to delete their audit logs
            $purchaseReturnIds = \DB::table('purchase_returns')->where('business_id', $businessId)->pluck('id');
            if ($purchaseReturnIds->isNotEmpty()) {
                \DB::table('purchase_return_audit_logs')->whereIn('purchase_return_id', $purchaseReturnIds)->delete();
            }

            // Phase 2: Delete Main Transactions
            \DB::table('sale_invoices')->where('business_id', $businessId)->delete();
            \DB::table('sale_returns')->where('business_id', $businessId)->delete();
            \DB::table('purchases')->where('business_id', $businessId)->delete();
            \DB::table('purchase_returns')->where('business_id', $businessId)->delete();
            \DB::table('quotations')->where('business_id', $businessId)->delete();
            \DB::table('approvals')->where('business_id', $businessId)->delete();

            // Phase 3: Delete Financial Transactions & Attachments
            
            // Expenses
            $expenseIds = \DB::table('expenses')->where('business_id', $businessId)->pluck('id');
            if ($expenseIds->isNotEmpty()) {
                \DB::table('expense_attachments')->whereIn('expense_id', $expenseIds)->delete();
            }
            \DB::table('expenses')->where('business_id', $businessId)->delete();

            // Other incomes
            $otherIncomeIds = \DB::table('other_incomes')->where('business_id', $businessId)->pluck('id');
            if ($otherIncomeIds->isNotEmpty()) {
                \DB::table('other_income_attachments')->whereIn('other_income_id', $otherIncomeIds)->delete();
            }
            \DB::table('other_incomes')->where('business_id', $businessId)->delete();

            // General vouchers
            $generalVoucherIds = \DB::table('general_vouchers')->where('business_id', $businessId)->pluck('id');
            if ($generalVoucherIds->isNotEmpty()) {
                \DB::table('general_voucher_attachments')->whereIn('general_voucher_id', $generalVoucherIds)->delete();
            }
            \DB::table('general_vouchers')->where('business_id', $businessId)->delete();

            // Bank transfers
            $bankTransferIds = \DB::table('bank_transfers')->where('business_id', $businessId)->pluck('id');
            if ($bankTransferIds->isNotEmpty()) {
                \DB::table('bank_transfer_attachments')->whereIn('bank_transfer_id', $bankTransferIds)->delete();
            }
            \DB::table('bank_transfers')->where('business_id', $businessId)->delete();

            // Party transfers
            $partyTransferIds = \DB::table('party_transfers')->where('business_id', $businessId)->pluck('id');
            if ($partyTransferIds->isNotEmpty()) {
                \DB::table('party_transfer_attachments')->whereIn('party_transfer_id', $partyTransferIds)->delete();
            }
            \DB::table('party_transfers')->where('business_id', $businessId)->delete();

            // Journal entries
            \DB::table('journal_entries')->where('business_id', $businessId)->delete();

            // Phase 4: Delete Ledger Entries
            \DB::table('bank_ledger')->where('business_id', $businessId)->delete();
            \DB::table('party_ledgers')->where('business_id', $businessId)->delete();

            // Phase 5: Delete Master Data
            \DB::table('banks')->where('business_id', $businessId)->delete();
            \DB::table('parties')->where('business_id', $businessId)->delete();
            \DB::table('expense_heads')->where('business_id', $businessId)->delete();
            \DB::table('income_heads')->where('business_id', $businessId)->delete();

            // Log the action
            Log::info('Business data completely cleared', [
                'business_id' => $businessId,
                'business_name' => $businessName,
                'cleared_by' => Auth::user()->id,
                'cleared_by_name' => Auth::user()->name,
                'timestamp' => now()
            ]);

            DB::commit();

            return redirect()->route('businesses.show', $business)
                ->with('success', 'All business data has been cleared successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to clear business data', [
                'business_id' => $business->id,
                'business_name' => $business->business_name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Failed to clear business data: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetCategoryRequest;
use App\Models\AssetCategory;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetCategoryController extends Controller
{
    public function index(Request $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $business = Business::findOrFail($businessId);

        $query = AssetCategory::forBusiness($businessId);

        if ($request->filled('asset_category_id')) {
            $query->where('asset_category_id', $request->asset_category_id);
        }

        if ($request->filled('asset_category')) {
            $query->where('asset_category', 'like', '%' . $request->asset_category . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $categories = $query->orderByDesc('asset_category_id')
            ->paginate(15)
            ->withQueryString();

        return view('asset-categories.index', compact('categories', 'business'));
    }

    public function create()
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        $business = Business::findOrFail($businessId);

        return view('asset-categories.create', compact('business'));
    }

    public function store(AssetCategoryRequest $request)
    {
        $businessId = session('active_business');
        if (!$businessId) {
            return redirect()->route('businesses.index')
                ->with('error', 'Please select a business first.');
        }

        AssetCategory::create([
            'business_id' => $businessId,
            'asset_category' => strtoupper($request->asset_category),
            'status' => $request->status ?? 1,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('asset-categories.index')
            ->with('success', 'Asset category has been added.');
    }

    public function edit(AssetCategory $assetCategory)
    {
        $businessId = session('active_business');
        if (!$businessId || $assetCategory->business_id != $businessId) {
            abort(403, 'Unauthorized access to this asset category.');
        }

        $business = Business::findOrFail($businessId);

        return view('asset-categories.edit', compact('assetCategory', 'business'));
    }

    public function update(AssetCategoryRequest $request, AssetCategory $assetCategory)
    {
        $businessId = session('active_business');
        if (!$businessId || $assetCategory->business_id != $businessId) {
            abort(403, 'Unauthorized access to this asset category.');
        }

        $assetCategory->update([
            'asset_category' => strtoupper($request->asset_category),
            'status' => $request->status ?? $assetCategory->status,
        ]);

        return redirect()->route('asset-categories.index')
            ->with('success', 'Asset category has been updated.');
    }

    public function destroy(AssetCategory $assetCategory)
    {
        $businessId = session('active_business');
        if (!$businessId || $assetCategory->business_id != $businessId) {
            abort(403, 'Unauthorized access to this asset category.');
        }

        $assetCategory->delete();

        return redirect()->route('asset-categories.index')
            ->with('success', 'Asset category has been deleted.');
    }
}


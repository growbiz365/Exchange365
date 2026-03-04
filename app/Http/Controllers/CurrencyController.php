<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        $currencies = Currency::query();

        // Search functionality
        if ($request->has('search')) {
            $currencies = $currencies->where('currency', 'like', '%' . $request->search . '%')
                ->orWhere('currency_symbol', 'like', '%' . $request->search . '%');
        }

        $currencies = $currencies->orderBy('currency')->paginate(10);

        return view('currencies.index', compact('currencies'));
    }

    public function create()
    {
        return view('currencies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|max:100',
            'currency_symbol' => 'nullable|string|max:10',
        ]);

        Currency::create($request->only(['currency', 'currency_symbol']));

        return redirect()->route('currencies.index')->with('success', 'Currency created successfully');
    }

    public function edit(Currency $currency)
    {
        return view('currencies.edit', compact('currency'));
    }

    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'currency' => 'required|string|max:100',
            'currency_symbol' => 'nullable|string|max:10',
        ]);

        $currency->update($request->only(['currency', 'currency_symbol']));

        return redirect()->route('currencies.index')->with('success', 'Currency updated successfully');
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();

        return redirect()->route('currencies.index')->with('success', 'Currency deleted successfully');
    }

    // Search for currencies using AJAX
    public function search(Request $request)
    {
        $query = $request->input('query');
        $currencyId = $request->input('id');

        if ($currencyId) {
            // If there's an ID, return the specific currency
            $currency = Currency::find($currencyId);
            return response()->json([
                'id' => $currency->currency_id,
                'name' => $currency->currency,
                'currency_code' => $currency->currency
            ]);
        } elseif ($query) {
            // Search currencies based on the query
            $currencies = Currency::where('currency', 'like', '%' . $query . '%')
                ->orWhere('currency_symbol', 'like', '%' . $query . '%')
                ->orderBy('currency')
                ->get(['currency_id', 'currency', 'currency_symbol']);

            $currencies = $currencies->map(function ($currency) {
                return [
                    'id' => $currency->currency_id,
                    'name' => $currency->currency,
                    'currency_code' => $currency->currency
                ];
            });

            return response()->json($currencies);
        }

        return response()->json([]);
    }
}

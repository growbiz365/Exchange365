<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_category_id' => 'required|exists:asset_categories,asset_category_id',
            'date_added' => 'required|date',
            'asset_name' => 'required|string|max:255',
            'cost_amount' => 'required|numeric|min:0.01',
            'purchase_transaction_type' => 'required|in:1,2,3',
            'purchase_bank_id' => 'required_if:purchase_transaction_type,2|nullable|integer',
            'purchase_party_id' => 'required_if:purchase_transaction_type,3|nullable|integer',
            'purchase_details' => 'nullable|string',
            'asset_status' => 'nullable|in:1,2',
        ];
    }

    public function attributes(): array
    {
        return [
            'asset_category_id' => 'asset category',
            'asset_name' => 'asset name',
            'cost_amount' => 'cost amount',
            'purchase_transaction_type' => 'purchase transaction type',
            'purchase_bank_id' => 'purchase bank',
            'purchase_party_id' => 'purchase party',
            'date_added' => 'purchase date',
        ];
    }
}


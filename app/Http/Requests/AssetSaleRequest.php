<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AssetSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->sale_date && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $this->sale_date)) {
            $this->merge(['sale_date' => Carbon::createFromFormat('d/m/Y', $this->sale_date)->format('Y-m-d')]);
        }
    }

    public function rules(): array
    {
        return [
            'sale_transaction_type' => 'required|in:2,3',
            'sale_date' => 'required|date',
            'sale_amount' => 'required|numeric|min:0.01',
            'sale_bank_id' => 'required_if:sale_transaction_type,2|nullable|integer',
            'sale_party_id' => 'required_if:sale_transaction_type,3|nullable|integer',
            'sale_details' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'sale_transaction_type' => 'sale transaction type',
            'sale_date' => 'sale date',
            'sale_amount' => 'sale amount',
            'sale_bank_id' => 'sale bank',
            'sale_party_id' => 'sale party',
        ];
    }
}


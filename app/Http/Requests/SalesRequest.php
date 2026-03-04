<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $businessId = session('active_business');

        return [
            'date_added' => ['required', 'date'],
            'bank_id' => [
                'required',
                Rule::exists('banks', 'bank_id')->where(fn ($q) => $q->where('business_id', $businessId)->where('status', 1)),
            ],
            'party_id' => [
                'required',
                Rule::exists('party', 'party_id')->where(fn ($q) => $q->where('business_id', $businessId)->where('status', 1)),
            ],
            'party_currency_id' => ['required', Rule::exists('currency', 'currency_id')],
            'transaction_operation' => ['required', 'in:1,2'],
            'currency_amount' => ['required', 'numeric', 'min:0.01'],
            'rate' => ['required', 'numeric', 'min:0.0001'],
            'party_amount' => ['required', 'numeric', 'min:0'],
            'details' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'date_added' => 'date',
            'bank_id' => 'bank',
            'party_id' => 'party',
            'party_currency_id' => 'party currency',
            'currency_amount' => 'currency amount',
            'party_amount' => 'party amount',
        ];
    }
}

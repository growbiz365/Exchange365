<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PurchaseRequest extends FormRequest
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
            'credit_amount' => ['required', 'numeric', 'min:0.01'],
            'rate' => ['required', 'numeric', 'min:0.0001'],
            'debit_amount' => ['required', 'numeric', 'min:0'],
            'details' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $date = $this->input('date_added');

        if (is_string($date) && $date !== '' && str_contains($date, '/')) {
            try {
                $this->merge([
                    'date_added' => Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d'),
                ]);
            } catch (\Throwable $e) {
                // Let validation handle invalid values
            }
        }
    }

    public function attributes(): array
    {
        return [
            'date_added' => 'date',
            'bank_id' => 'bank',
            'party_id' => 'party',
            'party_currency_id' => 'party currency',
            'credit_amount' => 'credit amount',
            'debit_amount' => 'debit amount',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bank_name' => 'required|string|max:255',
            'currency_id' => 'required|exists:currency,currency_id',
            'account_number' => 'nullable|string|max:100',
            'bank_type_id' => 'required|exists:bank_type,bank_type_id',
            'opening_balance' => 'required|numeric|min:0',
            'status' => 'nullable|in:0,1',
        ];
    }

    public function attributes(): array
    {
        return [
            'bank_name' => 'bank name',
            'currency_id' => 'currency',
            'bank_type_id' => 'bank type',
            'opening_balance' => 'opening balance',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $partyId = $this->route('party');

        return [
            'party_name' => 'required|string|max:255',
            'contact_no' => 'nullable|string|max:50',
            'party_type' => 'required|in:1,2',
            'opening_date' => 'required|date',
            'status' => 'nullable|in:0,1',
            
            // Opening balances (multiple currencies)
            'opening_balances' => 'nullable|array',
            'opening_balances.*.currency_id' => 'required|exists:currency,currency_id',
            'opening_balances.*.opening_balance' => 'required|numeric|min:0',
            'opening_balances.*.entry_type' => 'required|in:1,2',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'party_name' => 'party name',
            'contact_no' => 'contact number',
            'party_type' => 'party type',
            'opening_date' => 'opening date',
            'opening_balances.*.currency_id' => 'currency',
            'opening_balances.*.opening_balance' => 'opening balance',
            'opening_balances.*.entry_type' => 'entry type',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'opening_balances.*.currency_id.required' => 'The currency field is required.',
            'opening_balances.*.currency_id.exists' => 'The selected currency is invalid.',
            'opening_balances.*.opening_balance.required' => 'The opening balance field is required.',
            'opening_balances.*.opening_balance.numeric' => 'The opening balance must be a number.',
            'opening_balances.*.opening_balance.min' => 'The opening balance must be at least 0.',
            'opening_balances.*.entry_type.required' => 'The entry type field is required.',
            'opening_balances.*.entry_type.in' => 'The entry type must be either Credit or Debit.',
        ];
    }
}

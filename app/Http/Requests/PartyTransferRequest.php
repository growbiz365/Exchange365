<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartyTransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Convert d/m/Y date to Y-m-d before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->date_added) {
            $date = \DateTime::createFromFormat('d/m/Y', $this->date_added);
            if ($date) {
                $this->merge(['date_added' => $date->format('Y-m-d')]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'date_added' => 'required|date',
            'debit_party' => 'required|exists:party,party_id',
            'debit_currency_id' => 'required|exists:currency,currency_id',
            'debit_amount' => 'required|numeric|min:0.01',
            'credit_party' => 'required|exists:party,party_id|different:debit_party',
            'credit_currency_id' => 'required|exists:currency,currency_id',
            'credit_amount' => 'required|numeric|min:0.01',
            'rate' => 'required|integer|min:1',
            'transaction_operation' => 'nullable|in:1,2',
            'details' => 'nullable|string|max:1000',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120', // 5MB max
            'attachment_titles.*' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'date_added' => 'date',
            'debit_party' => 'debit party',
            'debit_currency_id' => 'debit currency',
            'debit_amount' => 'debit amount',
            'credit_party' => 'credit party',
            'credit_currency_id' => 'credit currency',
            'credit_amount' => 'credit amount',
            'attachments.*' => 'attachment',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'credit_party.different' => 'Credit party must be different from debit party.',
            'attachments.*.mimes' => 'Each attachment must be a file of type: jpg, jpeg, png, pdf, doc, docx, xls, xlsx.',
            'attachments.*.max' => 'Each attachment must not be greater than 5MB.',
        ];
    }
}

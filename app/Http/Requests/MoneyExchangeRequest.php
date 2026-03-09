<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoneyExchangeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_added' => 'required|date',
            'from_account_id' => 'required|exists:banks,bank_id',
            'to_account_id' => 'required|different:from_account_id|exists:banks,bank_id',
            'transaction_operation' => 'required|in:1,2',
            'debit_amount' => 'required|numeric|min:0.01',
            'credit_amount' => 'required|numeric|min:0.01',
            'rate' => 'required|numeric|min:0.0001',
            'details' => 'nullable|string|max:1000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120',
            'attachment_titles' => 'nullable|array',
            'attachment_titles.*' => 'nullable|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'date_added' => 'date',
            'from_account_id' => 'from account',
            'to_account_id' => 'to account',
            'debit_amount' => 'debit amount',
            'credit_amount' => 'credit amount',
            'attachments.*' => 'attachment',
        ];
    }

    public function messages(): array
    {
        return [
            'to_account_id.different' => 'To account must be different from from account.',
            'attachments.*.mimes' => 'Each attachment must be a file of type: jpg, jpeg, png, pdf, doc, docx, xls, xlsx.',
            'attachments.*.max' => 'Each attachment must not be greater than 5MB.',
        ];
    }
}


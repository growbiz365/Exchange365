<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BankTransferRequest extends FormRequest
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
            'from_account_id' => [
                'required',
                Rule::exists('banks', 'bank_id')->where(fn ($q) => $q->where('business_id', $businessId)->where('status', 1)),
            ],
            'to_account_id' => [
                'required',
                'different:from_account_id',
                Rule::exists('banks', 'bank_id')->where(fn ($q) => $q->where('business_id', $businessId)->where('status', 1)),
            ],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'details' => ['nullable', 'string', 'max:1000'],
            'attachments.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx', 'max:5120'],
            'attachment_titles.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $date = $this->input('date_added');
        if (is_string($date) && $date !== '') {
            try {
                // UI sends d/m/Y; backend expects Y-m-d
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
            'from_account_id' => 'from account',
            'to_account_id' => 'to account',
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


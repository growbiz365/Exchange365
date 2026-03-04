<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GeneralVoucherRequest extends FormRequest
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
            'entry_type' => ['required', 'in:1,2'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'rate' => ['required', 'numeric', 'min:0.0001'],
            'details' => ['nullable', 'string', 'max:1000'],
            'attachments.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx', 'max:5120'],
            'attachment_titles.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'date_added' => 'date',
            'bank_id' => 'bank',
            'party_id' => 'party',
            'entry_type' => 'entry type',
            'attachments.*' => 'attachment',
        ];
    }

    public function messages(): array
    {
        return [
            'attachments.*.mimes' => 'Each attachment must be a file of type: jpg, jpeg, png, pdf, doc, docx, xls, xlsx.',
            'attachments.*.max' => 'Each attachment must not be greater than 5MB.',
        ];
    }
}

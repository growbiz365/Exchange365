<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_category' => 'required|string|max:255',
            'status' => 'nullable|in:0,1',
        ];
    }

    public function attributes(): array
    {
        return [
            'asset_category' => 'asset category',
        ];
    }
}


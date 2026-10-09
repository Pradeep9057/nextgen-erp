<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomFieldStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'module_slug' => 'required|string|max:50',
            'field_name' => 'required|string|max:100',
            'field_type' => 'required|in:text,number,date,boolean,select',
            'is_required' => 'boolean',
            'options' => 'nullable|array',
        ];
    }
}
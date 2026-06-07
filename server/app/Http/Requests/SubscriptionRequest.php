<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token_id' => ['nullable', 'string'],
            'authentication_id' => ['nullable', 'string'],

            'plan_code' => ['required', 'string'],
            'billing_interval' => ['required', 'string'],
            'payment_method' => ['required', 'string'],

            // Agency data
            'agency_id' => ['nullable'],
            'agency_name' => ['nullable', 'string'],
            'agency_description' => ['nullable', 'string'],
            'agency_street' => ['nullable', 'string'],
            'agency_city' => ['nullable', 'string'],
            'agency_province' => ['nullable', 'string'],
            'agency_country' => ['nullable', 'string'],

            // Branch data
            'branch_name' => ['required', 'string', 'unique:branches,name'],
            'branch_street' => ['required', 'string'],
            'branch_description' => ['required', 'string'],
            'branch_city' => ['required', 'string'],
            'branch_province' => ['required', 'string'],
            'branch_country' => ['required', 'string'],
            'branch_contact_number' => ['nullable', 'string'],
            'branch_image' => ['nullable'],
            'branch_settings' => ['required', 'array'],
            'branch_settings.currency' => ['required', 'string'],
            'branch_settings.opening' => ['required', 'string'],
            'branch_settings.closing' => ['required', 'string'],
        ];
    }
}

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
            'agency_postal_code' => ['required_with:agency_name', 'string'],
            'agency_street' => ['required_with:agency_name', 'string'],
            'agency_city' => ['required_with:agency_name', 'string'],
            'agency_province' => ['required_with:agency_name', 'string'],
            'agency_country' => ['required_with:agency_name', 'string'],

            // Branch data
            'branch_name' => ['required', 'string', 'unique:branches,name'],
            'branch_postal_code' => ['required', 'string'],
            'branch_street' => ['required', 'string'],
            'branch_city' => ['required', 'string'],
            'branch_province' => ['required', 'string'],
            'branch_country' => ['required', 'string'],
            'branch_contact_number' => ['nullable', 'string'],
            'branch_image' => ['nullable'],
        ];
    }
}

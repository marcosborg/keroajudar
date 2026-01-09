<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBeneficiaryDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1'],
            'is_company' => ['nullable', 'boolean'],
            'nif' => ['nullable', 'string', 'max:50'],
            'nipc' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:30'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'consent_privacy' => ['accepted'],
        ];
    }
}

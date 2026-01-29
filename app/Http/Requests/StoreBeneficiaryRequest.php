<?php

namespace App\Http\Requests;

use App\Models\Beneficiary;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreBeneficiaryRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('beneficiary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'beneficiary_category_id' => [
                'required',
                'integer',
            ],
            'name' => [
                'string',
                'required',
            ],
            'description' => [
                'nullable',
            ],
            'about' => [
                'nullable',
            ],
            'vat_number' => [
                'required',
                'string',
                'max:64',
                'regex:/^\\d{9}$/',
            ],
            'commercial_certificate_code' => [
                'required',
                'string',
                'max:20',
                'regex:/^\\d{4}-\\d{4}-\\d{4}$/',
            ],
            'iban' => [
                'required',
                'string',
                'max:34',
                'regex:/^[A-Z]{2}\\d{2}[A-Z0-9]{11,30}$/',
            ],
            'contact_email' => [
                'nullable',
                'string',
                'email',
                'max:255',
            ],
            'contact_phone' => [
                'nullable',
                'string',
                'max:64',
            ],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                'unique:beneficiaries,email',
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
            ],
            'website' => [
                'nullable',
                'string',
                'max:255',
            ],
            'address' => [
                'nullable',
                'string',
                'max:255',
            ],
            'postal_code' => [
                'required',
                'string',
                'max:16',
                'regex:/^\\d{4}-\\d{3}$/',
            ],
            'city' => [
                'nullable',
                'string',
                'max:128',
            ],
            'country' => [
                'nullable',
                'string',
                'max:128',
            ],
            'active' => [
                'boolean',
            ],
        ];
    }
}

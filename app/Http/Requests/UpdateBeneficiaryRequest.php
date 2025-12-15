<?php

namespace App\Http\Requests;

use App\Models\Beneficiary;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateBeneficiaryRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('beneficiary_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
                'nullable',
                'string',
                'max:64',
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

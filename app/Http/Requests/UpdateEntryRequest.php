<?php

namespace App\Http\Requests;

use App\Models\Entry;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEntryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('entry_edit');
    }

    public function rules()
    {
        return [
            'raffle_code' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
            ],
            'first_name' => [
                'string',
                'required',
            ],
            'last_name' => [
                'string',
                'required',
            ],
            'phone' => [
                'string',
                'nullable',
            ],
            'amount' => [
                'required',
            ],
            'nif' => [
                'string',
                'nullable',
            ],
            'nipc' => [
                'string',
                'nullable',
            ],
            'address' => [
                'string',
                'nullable',
            ],
            'postal_code' => [
                'string',
                'nullable',
            ],
            'city' => [
                'string',
                'required',
            ],
            'country_id' => [
                'required',
                'integer',
            ],
            'consent_privacy' => [
                'required',
            ],
            'source_page' => [
                'string',
                'nullable',
            ],
            'client_ip' => [
                'string',
                'nullable',
            ],
            'user_agent' => [
                'string',
                'nullable',
            ],
        ];
    }
}

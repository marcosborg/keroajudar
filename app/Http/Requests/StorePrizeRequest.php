<?php

namespace App\Http\Requests;

use App\Models\Prize;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePrizeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('prize_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
        ];
    }
}

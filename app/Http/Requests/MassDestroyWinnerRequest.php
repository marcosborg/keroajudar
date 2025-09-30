<?php

namespace App\Http\Requests;

use App\Models\Winner;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyWinnerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('winner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:winners,id',
        ];
    }
}

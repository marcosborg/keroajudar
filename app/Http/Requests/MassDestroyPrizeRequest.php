<?php

namespace App\Http\Requests;

use App\Models\Prize;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPrizeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('prize_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:prizes,id',
        ];
    }
}

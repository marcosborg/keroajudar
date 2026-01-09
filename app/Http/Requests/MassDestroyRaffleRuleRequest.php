<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyRaffleRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        abort_if(Gate::denies('raffle_rule_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(): array
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:raffle_rules,id',
        ];
    }
}

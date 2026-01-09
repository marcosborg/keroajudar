<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRaffleRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('raffle_rule_edit');
    }

    public function rules(): array
    {
        return [
            'raffle_game_id' => [
                'required',
                'integer',
                'exists:raffle_games,id',
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'numbers' => [
                'required',
                'integer',
                'min:1',
            ],
            'active' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}

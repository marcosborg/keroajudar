<?php

namespace App\Http\Requests;

use App\Models\RaffleRule;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreRaffleRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('raffle_rule_create');
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

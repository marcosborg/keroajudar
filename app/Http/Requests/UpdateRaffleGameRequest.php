<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRaffleGameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('raffle_game_edit');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'prize_id' => [
                'nullable',
                'integer',
                'exists:prizes,id',
            ],
            'starts_at' => [
                'required',
                'date',
            ],
            'ends_at' => [
                'required',
                'date',
                'after:starts_at',
            ],
            'active' => [
                'nullable',
                'boolean',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
}

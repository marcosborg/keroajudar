<?php

namespace App\Http\Requests;

use App\Models\Winner;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateWinnerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('winner_edit');
    }

    public function rules()
    {
        return [
            'entry_id' => [
                'required',
                'integer',
            ],
            'prize_id' => [
                'required',
                'integer',
            ],
            'draw_date' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
        ];
    }
}

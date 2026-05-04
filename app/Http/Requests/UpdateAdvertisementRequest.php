<?php

namespace App\Http\Requests;

use App\Models\Advertisement;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdvertisementRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('advertisement_edit');
    }

    public function rules()
    {
        return [
            'type' => ['required', Rule::in(array_keys(Advertisement::TYPES))],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'draw_date' => ['nullable', 'date'],
            'link_url' => ['nullable', 'url', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'string'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->has('beneficiary') && ! $this->hasValidSignature()) {
            abort(403, 'A ligação da associação não é válida.');
        }

        return true;
    }

    protected function prepareForValidation(): void
    {
        $prepared = [
            'targets_all_categories' => $this->boolean('targets_all_categories'),
            'targets_all_regions' => $this->boolean('targets_all_regions'),
            'consent_terms' => $this->boolean('consent_terms'),
        ];

        if ($this->has('beneficiary') && $this->hasValidSignature()) {
            $prepared['beneficiary_id'] = $this->query('beneficiary');
        }

        $this->merge($prepared);
    }

    public function rules(): array
    {
        $position = $this->route('position');
        $desktopWidth = (int) $position->desktop_width;
        $desktopHeight = (int) $position->desktop_height;
        $mobileWidth = (int) $position->mobile_width;
        $mobileHeight = (int) $position->mobile_height;

        return [
            'customer_type' => ['required', Rule::in(['individual', 'company'])],
            'contact_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'required_if:customer_type,company', 'string', 'max:255'],
            'tax_number' => ['required', 'regex:/^[0-9]{9}$/'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['required', 'regex:/^[0-9+ ()-]{9,30}$/'],
            'address' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'regex:/^[0-9]{4}-[0-9]{3}$/'],
            'city' => ['required', 'string', 'max:120'],
            'country' => ['required', 'string', 'max:120'],
            'campaign_title' => ['required', 'string', 'max:255'],
            'destination_url' => ['required', 'url:http,https', 'max:2048'],
            'beneficiary_id' => [
                'required',
                Rule::exists('beneficiaries', 'id')->where(fn ($query) => $query
                    ->where('active', true)
                    ->whereNull('deleted_at')),
            ],
            'desktop_creative' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120', "dimensions:width={$desktopWidth},height={$desktopHeight}"],
            'mobile_creative' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120', "dimensions:width={$mobileWidth},height={$mobileHeight}"],
            'starts_at' => ['required', 'date', 'after_or_equal:today'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'targets_all_categories' => ['required', 'boolean'],
            'target_categories' => ['nullable', 'required_if:targets_all_categories,false', 'array', 'min:1'],
            'target_categories.*' => ['string', Rule::in(array_keys(config('advertising.categories')))],
            'targets_all_regions' => ['required', 'boolean'],
            'target_regions' => ['nullable', 'required_if:targets_all_regions,false', 'array', 'min:1'],
            'target_regions.*' => ['string', Rule::in(array_keys(config('advertising.regions')))],
            'payment_method' => ['required', Rule::in(['multibanco', 'mbway'])],
            'mbway_phone' => ['nullable', 'required_if:payment_method,mbway', 'digits:9'],
            'consent_terms' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'tax_number.regex' => 'Indique um NIF/NIPC português com 9 algarismos.',
            'postal_code.regex' => 'Use o formato de código postal 0000-000.',
            'target_categories.required_if' => 'Selecione pelo menos uma categoria.',
            'target_regions.required_if' => 'Selecione pelo menos uma região.',
            'mbway_phone.required_if' => 'Indique o número associado ao MB WAY.',
            'consent_terms.accepted' => 'Tem de aceitar as condições do pedido.',
            'beneficiary_id.required' => 'Escolha a associação que pretende apoiar.',
            'beneficiary_id.exists' => 'A associação escolhida não está disponível.',
            'desktop_creative.required' => 'Carregue o criativo para desktop.',
            'desktop_creative.image' => 'O criativo desktop tem de ser uma imagem.',
            'desktop_creative.mimes' => 'O criativo desktop tem de ser JPEG, PNG ou WebP.',
            'desktop_creative.max' => 'O criativo desktop não pode exceder 5 MB.',
            'desktop_creative.dimensions' => 'O criativo desktop tem de ter exatamente :width×:height píxeis.',
            'mobile_creative.required' => 'Carregue o criativo para telemóvel.',
            'mobile_creative.image' => 'O criativo mobile tem de ser uma imagem.',
            'mobile_creative.mimes' => 'O criativo mobile tem de ser JPEG, PNG ou WebP.',
            'mobile_creative.max' => 'O criativo mobile não pode exceder 5 MB.',
            'mobile_creative.dimensions' => 'O criativo mobile tem de ter exatamente :width×:height píxeis.',
        ];
    }
}

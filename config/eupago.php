<?php

return [
    'api_key' => env('EUPAGO_API_KEY'),
    'sandbox' => (bool) env('EUPAGO_SANDBOX', true),
    'callback_url' => env('EUPAGO_CALLBACK_URL', env('APP_URL') . '/api/eupago/callback'),
    'endpoints' => [
        'split_multibanco' => '/api/v1/split-payments/multibanco',
        'split_mbway'      => '/api/v1/split-payments/mbway',
        'webhook'          => '/api/eupago/callback',
    ],
    'base_url' => env('EUPAGO_SANDBOX', true)
        ? 'https://sandbox.eupago.pt'
        : 'https://clientes.eupago.pt',
];

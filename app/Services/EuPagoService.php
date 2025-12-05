<?php

namespace App\Services;

use App\Models\Pedido;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EuPagoService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $callbackUrl;

    public function __construct()
    {
        $this->apiKey = (string) config('eupago.api_key');
        $this->baseUrl = (string) config('eupago.base_url');
        $this->callbackUrl = (string) config('eupago.callback_url');
    }

    public function createMultibancoSplitPayment(array $data): array
    {
        $pedido = Pedido::firstOrCreate(
            ['identificador' => $data['identificador']],
            [
                'status'        => 'pendente',
                'valor'         => $data['valor'],
                'beneficiarios' => $data['beneficiarios'],
            ]
        );

        if ($pedido->status === 'pago') {
            return [
                'status'    => 'pago',
                'entidade'  => $pedido->entidade,
                'referencia'=> $pedido->referencia,
                'valor'     => $pedido->valor,
            ];
        }

        $payload = [
            'valor'          => $data['valor'],
            'identificador'  => $data['identificador'],
            'beneficiarios'  => $data['beneficiarios'],
            'callback'       => $this->callbackUrl,
        ];

        if (!empty($data['data_limite'])) {
            $payload['data_limite'] = $data['data_limite'];
        }
        if (isset($data['permite_multiplos'])) {
            $payload['permite_multiplos'] = $data['permite_multiplos'];
        }

        $response = $this->post(config('eupago.endpoints.split_multibanco'), $payload);

        $body = $response->json();

        if (!isset($body['entidade'], $body['referencia'])) {
            $this->logError('Resposta inesperada MB split', $body);
            throw new RequestException($response);
        }

        $pedido->update([
            'entidade'         => $body['entidade'] ?? null,
            'referencia'       => $body['referencia'] ?? null,
            'mp'               => $body['mp'] ?? 'PC:PT',
            'provider_payload' => $body,
        ]);

        return [
            'status'     => 'pendente',
            'entidade'   => $pedido->entidade,
            'referencia' => $pedido->referencia,
            'valor'      => $pedido->valor,
        ];
    }

    public function createMbwaySplitPayment(array $data): array
    {
        $pedido = Pedido::firstOrCreate(
            ['identificador' => $data['identificador']],
            [
                'status'        => 'pendente_callback',
                'valor'         => $data['valor'],
                'beneficiarios' => $data['beneficiarios'],
            ]
        );

        if ($pedido->status === 'pago') {
            return ['status' => 'pago', 'valor' => $pedido->valor];
        }

        $payload = [
            'valor'          => $data['valor'],
            'identificador'  => $data['identificador'],
            'alias'          => $data['alias'],
            'beneficiarios'  => $data['beneficiarios'],
            'callback'       => $this->callbackUrl,
        ];
        if (!empty($data['descricao'])) {
            $payload['descricao'] = $data['descricao'];
        }

        $response = $this->post(config('eupago.endpoints.split_mbway'), $payload);
        $body = $response->json();

        $pedido->update([
            'mp'               => $body['mp'] ?? 'MW:PT',
            'transacao'        => $body['transacao'] ?? null,
            'provider_payload' => $body,
            'status'           => 'pendente_callback',
        ]);

        return [
            'status'     => 'pendente_callback',
            'transacao'  => $pedido->transacao,
            'valor'      => $pedido->valor,
        ];
    }

    public function validateWebhook(Request $request)
    {
        $key = $request->query('chave_api');
        if (!$key || $key !== $this->apiKey) {
            $this->logError('Webhook com chave inválida', ['ip' => $request->ip(), 'payload' => $request->query()]);
            return response('unauthorized', 403);
        }

        $identificador = $request->query('identificador');
        $pedido = Pedido::where('identificador', $identificador)->first();

        if (!$pedido) {
            $this->logError('Webhook sem pedido correspondente', ['identificador' => $identificador, 'payload' => $request->query()]);
            return response('ok', 200);
        }

        if ($pedido->status === 'pago') {
            $this->logError('Webhook duplicado', ['identificador' => $identificador]);
            return response('ok', 200);
        }

        $pedido->update([
            'status'           => 'pago',
            'entidade'         => $request->query('entidade', $pedido->entidade),
            'referencia'       => $request->query('referencia', $pedido->referencia),
            'mp'               => $request->query('mp', $pedido->mp),
            'transacao'        => $request->query('transacao', $pedido->transacao),
            'data_pagamento'   => now(),
            'callback_payload' => $request->query(),
        ]);

        return response('ok', 200);
    }

    protected function post(string $endpoint, array $payload)
    {
        $url = Str::finish($this->baseUrl, '/') . ltrim($endpoint, '/');

        $response = Http::withHeaders([
            'ApiKey' => $this->apiKey,
        ])->acceptJson()->post($url, $payload);

        if ($response->failed()) {
            $code = $response->json('codigo') ?? $response->status();
            $this->logError('Erro EuPago', ['code' => $code, 'body' => $response->json(), 'payload' => $payload]);
            $response->throw();
        }

        $body = $response->json();
        if (isset($body['codigo']) && $body['codigo'] < 0) {
            $this->logError('Código erro EuPago', ['code' => $body['codigo'], 'body' => $body]);
            $response->throw();
        }

        return $response;
    }

    protected function logError(string $message, array $context = []): void
    {
        Log::channel('eupago')->error($message, $context);
    }
}

@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Pedido #{{ $pedido->id }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <a class="btn btn-default" href="{{ route('admin.pedidos.index') }}">
                Voltar à lista
            </a>
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>ID</th>
                    <td>{{ $pedido->id }}</td>
                </tr>
                <tr>
                    <th>Identificador</th>
                    <td>{{ $pedido->identificador }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $pedido->status }}</td>
                </tr>
                <tr>
                    <th>Valor</th>
                    <td>{{ number_format($pedido->valor, 2) }}</td>
                </tr>
                <tr>
                    <th>Entidade</th>
                    <td>{{ $pedido->entidade }}</td>
                </tr>
                <tr>
                    <th>Referência</th>
                    <td>{{ $pedido->referencia }}</td>
                </tr>
                <tr>
                    <th>MP</th>
                    <td>{{ $pedido->mp }}</td>
                </tr>
                <tr>
                    <th>Transação</th>
                    <td>{{ $pedido->transacao }}</td>
                </tr>
                <tr>
                    <th>Beneficiários</th>
                    <td><pre class="mb-0">{{ json_encode($pedido->beneficiarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></td>
                </tr>
                <tr>
                    <th>Pago em</th>
                    <td>{{ $pedido->data_pagamento }}</td>
                </tr>
                <tr>
                    <th>Callback payload</th>
                    <td><pre class="mb-0">{{ json_encode($pedido->callback_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></td>
                </tr>
                <tr>
                    <th>Provider payload</th>
                    <td><pre class="mb-0">{{ json_encode($pedido->provider_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></td>
                </tr>
                <tr>
                    <th>Criado em</th>
                    <td>{{ $pedido->created_at }}</td>
                </tr>
                <tr>
                    <th>Atualizado em</th>
                    <td>{{ $pedido->updated_at }}</td>
                </tr>
            </tbody>
        </table>
        <div class="form-group">
            <a class="btn btn-default" href="{{ route('admin.pedidos.index') }}">
                Voltar à lista
            </a>
        </div>
    </div>
</div>

@endsection

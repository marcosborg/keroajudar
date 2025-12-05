@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Pedidos EuPago
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Identificador</th>
                        <th>Status</th>
                        <th>Valor</th>
                        <th>Entidade</th>
                        <th>Referência</th>
                        <th>MP</th>
                        <th>Transação</th>
                        <th>Pago em</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->identificador }}</td>
                            <td><span class="badge badge-{{ $pedido->status === 'pago' ? 'success' : 'secondary' }}">{{ $pedido->status }}</span></td>
                            <td>{{ number_format($pedido->valor, 2) }}</td>
                            <td>{{ $pedido->entidade }}</td>
                            <td>{{ $pedido->referencia }}</td>
                            <td>{{ $pedido->mp }}</td>
                            <td>{{ $pedido->transacao }}</td>
                            <td>{{ $pedido->data_pagamento }}</td>
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.pedidos.show', $pedido->id) }}">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $pedidos->links() }}
        </div>
    </div>
</div>

@endsection

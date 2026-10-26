@extends('layouts.admin')
@section('content')
<div class="card"><div class="card-header">Pedidos de publicidade</div><div class="card-body">
<div class="table-responsive"><table class="table table-bordered table-striped table-hover"><thead><tr><th>Data</th><th>Cliente</th><th>Posição</th><th>Período</th><th>Total</th><th>Pagamento</th><th>Estado</th><th></th></tr></thead><tbody>
@forelse($orders as $order)
<tr><td>{{ $order->created_at->format('d/m/Y H:i') }}</td><td>{{ $order->company_name ?: $order->contact_name }}<br><small>{{ $order->email }}</small></td><td>{{ $order->position->name }}</td><td>{{ $order->starts_at->format('d/m/Y') }} – {{ $order->ends_at->format('d/m/Y') }}</td><td>{{ number_format((float) $order->amount, 2, ',', '.') }} €</td><td>{{ $order->payment_method === 'mbway' ? 'MB WAY' : 'Multibanco' }}</td><td><span class="badge badge-{{ $order->status === 'paid' ? 'success' : ($order->status === 'payment_failed' ? 'danger' : 'warning') }}">{{ \App\Models\AdOrder::STATUSES[$order->status] ?? $order->status }}</span></td><td><a class="btn btn-xs btn-primary" href="{{ route('admin.ad-orders.show', $order) }}">Ver</a></td></tr>
@empty<tr><td colspan="8" class="text-center text-muted">Ainda não existem pedidos.</td></tr>@endforelse
</tbody></table></div>{{ $orders->links() }}
</div></div>
@endsection

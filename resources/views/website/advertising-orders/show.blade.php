@extends('layouts.website')

@section('content')
<main class="py-5 advertising-order-page"><div class="container" style="max-width:820px">
    <div class="card border-0 shadow-sm"><div class="card-body p-4 p-lg-5">
        @if($order->status === \App\Models\AdOrder::STATUS_PAID)
            <div class="text-success display-5 mb-3"><i class="bi bi-check-circle"></i></div><h1 class="h2">Pagamento confirmado</h1><p>A campanha foi criada em rascunho e será preparada para publicação.</p>
        @elseif($order->status === \App\Models\AdOrder::STATUS_PAYMENT_FAILED)
            <div class="text-danger display-5 mb-3"><i class="bi bi-exclamation-circle"></i></div><h1 class="h2">Não foi possível iniciar o pagamento</h1><p>{{ $order->failure_message }}</p>
        @else
            <div class="text-warning display-5 mb-3"><i class="bi bi-hourglass-split"></i></div><h1 class="h2">Pagamento pendente</h1><p>O pedido foi registado. Complete o pagamento com os dados abaixo.</p>
        @endif
        <dl class="row mt-4"><dt class="col-sm-5">Referência do pedido</dt><dd class="col-sm-7">{{ $order->public_id }}</dd><dt class="col-sm-5">Posição</dt><dd class="col-sm-7">{{ $order->position->name }}</dd><dt class="col-sm-5">Período</dt><dd class="col-sm-7">{{ $order->starts_at->format('d/m/Y') }} – {{ $order->ends_at->format('d/m/Y') }}</dd><dt class="col-sm-5">Total</dt><dd class="col-sm-7 fw-bold">{{ number_format((float) $order->amount, 2, ',', '.') }} €</dd></dl>
        @if($order->payment && $order->payment_method === 'multibanco')
            <div class="payment-reference"><h2 class="h5">Referência Multibanco</h2><dl class="row mb-0"><dt class="col-5">Entidade</dt><dd class="col-7">{{ $order->payment->entidade }}</dd><dt class="col-5">Referência</dt><dd class="col-7">{{ $order->payment->referencia }}</dd><dt class="col-5">Valor</dt><dd class="col-7">{{ number_format((float) $order->payment->valor, 2, ',', '.') }} €</dd></dl></div>
        @elseif($order->payment && $order->payment_method === 'mbway' && $order->status !== \App\Models\AdOrder::STATUS_PAID)
            <div class="alert alert-info">Confirme o pedido recebido na aplicação MB WAY.</div>
        @endif
        <p class="small text-muted mt-4 mb-0">Guarde esta página para consultar o estado do pedido. O contacto associado é {{ $order->email }}.</p>
    </div></div>
</div></main>
@endsection

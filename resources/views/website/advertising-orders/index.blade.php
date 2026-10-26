@extends('layouts.website')

@section('content')
<main class="py-5 advertising-order-page">
    <div class="container">
        <div class="mb-4">
            <span class="advertising-kicker">Publicidade na Kero Ajudar</span>
            <h1 class="h2 mt-1">Escolha a posição pretendida</h1>
            <p class="text-muted mb-0">O valor final é calculado pelo número de dias. Depois de preencher os dados, o pagamento é iniciado imediatamente por Multibanco ou MB WAY.</p>
        </div>
        <div class="row g-4">
            @foreach($positions as $position)
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 shadow-sm border-0 ad-order-position-card">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div><h2 class="h5 mb-1">{{ $position->name }}</h2><span class="text-muted">{{ $position->code }}</span></div>
                                <span class="badge bg-success">Faixa {{ $position->row }}</span>
                            </div>
                            <hr>
                            <p class="mb-1"><strong>Desktop:</strong> {{ $position->desktopDimensions() }}</p>
                            <p><strong>Telemóvel:</strong> {{ $position->mobileDimensions() }}</p>
                            <div class="mt-auto">
                                @if((float) $position->daily_price > 0)
                                    <p class="h5 text-success">{{ number_format((float) $position->daily_price, 2, ',', '.') }} € <small class="text-muted fs-6">/ dia</small></p>
                                    <a class="btn btn-success w-100" href="{{ route('advertising.orders.create', $position) }}">Pedir esta posição</a>
                                @else
                                    <p class="text-muted">Preço em configuração</p>
                                    <a class="btn btn-outline-success w-100" href="{{ route('website.contactos') }}">Pedir informação</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</main>
@endsection

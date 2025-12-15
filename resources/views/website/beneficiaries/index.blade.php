@extends('layouts.website')
@php use Illuminate\Support\Str; @endphp

@section('content')
<main class="py-5">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
            <div>
                <p class="text-uppercase text-success fw-semibold mb-1">Beneficiários</p>
                <h1 class="mb-2">Encontre quem quer apoiar</h1>
                <p class="text-muted mb-0">Veja a lista por categoria e partilhe a causa. Se é beneficiário, faça login ou crie a sua conta.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('beneficiaries.login') }}" class="btn btn-outline-success">Login beneficiário</a>
                <a href="{{ route('beneficiaries.register') }}" class="btn btn-success">Criar conta</a>
            </div>
        </div>

        @foreach($categories as $category)
            <section class="mb-5">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h4 class="mb-1">{{ $category->name }}</h4>
                        @if($category->description)
                            <p class="text-muted mb-0">{{ Str::limit($category->description, 140) }}</p>
                        @endif
                    </div>
                </div>
                <div class="row g-3">
                    @forelse($category->beneficiaries as $beneficiary)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-img-top ratio ratio-16x9" style="background: #e9f6ef url('{{ $beneficiary->cover_url }}') center/cover no-repeat;"></div>
                                <div class="card-body">
                                    <h5 class="card-title mb-1">{{ $beneficiary->name }}</h5>
                                    @if($beneficiary->description)
                                        <p class="card-text text-muted small mb-2">{{ Str::limit($beneficiary->description, 110) }}</p>
                                    @endif
                                    @if($beneficiary->city || $beneficiary->country)
                                        <p class="text-success small mb-0"><i class="bi bi-geo-alt-fill"></i> {{ $beneficiary->city }} {{ $beneficiary->country }}</p>
                                    @endif
                                </div>
                                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                                    <a href="{{ route('website.donativo', ['beneficiary_id' => $beneficiary->id]) }}" class="btn btn-sm btn-success">Apoiar agora</a>
                                    <span class="text-muted small">ID: {{ $beneficiary->id }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-light border">Ainda não há beneficiários nesta categoria.</div>
                        </div>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>
</main>
@endsection

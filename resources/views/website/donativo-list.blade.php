@extends('layouts.website')
@php use Illuminate\Support\Str; @endphp

@section('content')
<main class="donativo-page">
    <div class="container">
        <div class="donativo-header">
            <p class="text-uppercase text-success fw-semibold mb-1">Donativo</p>
            <h2 class="mb-0">Escolha uma categoria e encontre quem apoiar</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="category-panel">
                    <div class="category-panel__header">
                        <p class="text-uppercase text-success fw-semibold mb-1">Categorias</p>
                        <h3 class="mb-0">Filtrar beneficiarios</h3>
                    </div>
                    @php $fallback = asset('images/banner-ajuda.png'); @endphp
                    <div class="category-list" id="categoryFilter">
                        <button class="category-item active" data-category="all" type="button">
                            <span class="category-icon" style="background-image: url('{{ $fallback }}');"></span>
                            <span class="category-info">
                                <span class="category-name">Todas</span>
                                <span class="category-meta">Ver todas as causas</span>
                            </span>
                            <span class="category-action"><i class="bi bi-chevron-right"></i></span>
                        </button>
                        @foreach($categories as $category)
                            @php
                                $thumb = $category->image?->thumbnail ?? $category->cover_url ?? $fallback;
                            @endphp
                            <button class="category-item" data-category="{{ $category->id }}" type="button">
                                <span class="category-icon" style="background-image: url('{{ $thumb }}');"></span>
                                <span class="category-info">
                                    <span class="category-name">{{ $category->name }}</span>
                                    <span class="category-meta">{{ $category->beneficiaries->count() }} beneficiario(s)</span>
                                </span>
                                <span class="category-action"><i class="bi bi-chevron-right"></i></span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="beneficiary-panel">
                    <div class="beneficiary-panel__header">
                        <p class="text-uppercase text-success fw-semibold mb-1">Beneficiarios</p>
                        <h3 class="mb-0">Escolha quem apoiar</h3>
                    </div>
                    <div class="list-group shadow-sm" id="beneficiaryList">
                        @foreach($beneficiaries as $beneficiary)
                            <a class="list-group-item list-group-item-action d-flex align-items-center justify-content-between beneficiary-item" data-category="{{ $beneficiary->beneficiary_category_id }}" href="{{ route('website.beneficiary.donate', ['beneficiary' => $beneficiary->id, 'slug' => Str::slug($beneficiary->name)]) }}">
                                <div class="d-flex align-items-center gap-3">
                                    @php $logo = $beneficiary->logo_square?->thumbnail ?? $beneficiary->photo?->thumbnail ?? asset('images/banner-ajuda.png'); @endphp
                                    <div class="rounded-circle overflow-hidden beneficiary-avatar" style="background-image: url('{{ $logo }}');"></div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-success">{{ $beneficiary->category->name ?? '' }}</span>
                                            <span class="text-muted small">#{{ $beneficiary->id }}</span>
                                        </div>
                                        <h6 class="mb-0">{{ $beneficiary->name }}</h6>
                                        @if($beneficiary->city || $beneficiary->country)
                                            <p class="text-muted small mb-0"><i class="bi bi-geo-alt-fill"></i> {{ $beneficiary->city }} {{ $beneficiary->country }}</p>
                                        @endif
                                    </div>
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script>
    (function(){
        const buttons = document.querySelectorAll('#categoryFilter .category-item');
        const items = document.querySelectorAll('.beneficiary-item');

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const cat = btn.dataset.category;
                items.forEach(item => {
                    const match = cat === 'all' || item.dataset.category === cat;
                    item.classList.toggle('d-none', !match);
                });
            });
        });
    })();
</script>
@endpush
@push('styles')
<style>
    .donativo-page {
        padding: 1.5rem 0 3.5rem;
    }
    .donativo-header {
        margin: 0 0 2rem;
    }
    .donativo-header p {
        margin-bottom: 0.35rem;
    }
    .donativo-header h2,
    .donativo-header p {
        margin-top: 0;
    }
    .category-panel {
        background: #fff;
        border: 1px solid #e6f0ea;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 10px 28px rgba(15,23,42,0.08);
    }
    .category-panel__header {
        margin-bottom: 1.25rem;
    }
    .category-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .category-item {
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid #eef2f1;
        background: #fff;
        border-radius: 14px;
        padding: 10px 12px;
        text-align: left;
        transition: background 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .category-item:hover {
        background: #f8fdf9;
        border-color: #dfe9e3;
    }
    .category-item.active {
        background: #e9f7f0;
        border-color: #198754;
        box-shadow: inset 4px 0 0 #198754;
    }
    .category-item:focus-visible {
        outline: none;
        box-shadow: 0 0 0 3px rgba(25,135,84,0.2);
    }
    .category-icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background-size: cover;
        background-position: center;
        flex-shrink: 0;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.35), 0 4px 10px rgba(0,0,0,0.12);
    }
    .category-info {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    .category-name {
        font-weight: 700;
        color: #0f172a;
    }
    .category-meta {
        color: #64748b;
        font-size: 0.85rem;
    }
    .category-action {
        margin-left: auto;
        color: #94a3b8;
        font-size: 1rem;
    }
    .category-item.active .category-action {
        color: #198754;
    }
    .beneficiary-panel__header {
        margin-bottom: 1.25rem;
    }
    .beneficiary-avatar {
        width: 56px;
        height: 56px;
        background: #e9f6ef center/cover no-repeat;
    }
    #beneficiaryList .list-group-item {
        border: 0;
        border-bottom: 1px solid #f1f1f1;
        padding: 14px 16px;
    }
    #beneficiaryList .list-group-item:last-child {
        border-bottom: 0;
    }
    #beneficiaryList .list-group-item:hover {
        background: #f8fdf9;
    }
    @media (max-width: 991px) {
        .donativo-page {
            padding-top: 1rem;
        }
    }
</style>
@endpush
@endsection

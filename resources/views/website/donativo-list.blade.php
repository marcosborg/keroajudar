@extends('layouts.website')
@php use Illuminate\Support\Str; @endphp

@section('content')
<main class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <p class="text-uppercase text-success fw-semibold mb-1">Categorias</p>
                        <h3 class="mb-3">Encontre um beneficiário</h3>
                        @php $fallback = asset('images/banner-ajuda.png'); @endphp
                        <div class="list-group category-list" id="categoryFilter">
                            <button class="list-group-item category-item active" data-category="all">
                                <div class="d-flex align-items-center gap-3 w-100">
                                    <div class="category-thumb" style="background-image: url('{{ $fallback }}');"></div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">Todas</div>
                                        <div class="text-muted small">Ver todas as causas</div>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </div>
                            </button>
                            @foreach($categories as $category)
                                @php
                                    $thumb = $category->image?->thumbnail ?? $category->cover_url ?? $fallback;
                                @endphp
                                <button class="list-group-item category-item" data-category="{{ $category->id }}">
                                    <div class="d-flex align-items-center gap-3 w-100">
                                        <div class="category-thumb" style="background-image: url('{{ $thumb }}');"></div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">{{ $category->name }}</div>
                                            <div class="text-muted small">{{ $category->beneficiaries->count() }} beneficiário(s)</div>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted"></i>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="text-uppercase text-success fw-semibold mb-1">Beneficiários</p>
                        <h3 class="mb-0">Escolha quem apoiar</h3>
                    </div>
                </div>
                <div class="list-group shadow-sm" id="beneficiaryList">
                    @foreach($beneficiaries as $beneficiary)
                        <a class="list-group-item list-group-item-action d-flex align-items-center justify-content-between beneficiary-item" data-category="{{ $beneficiary->beneficiary_category_id }}" href="{{ route('website.beneficiary.donate', ['beneficiary' => $beneficiary->id, 'slug' => Str::slug($beneficiary->name)]) }}">
                            <div class="d-flex align-items-center gap-3">
                                @php $logo = $beneficiary->logo_square?->thumbnail ?? $beneficiary->photo?->thumbnail ?? asset('images/banner-ajuda.png'); @endphp
                                <div class="rounded-circle overflow-hidden" style="width:56px;height:56px;background:#e9f6ef url('{{ $logo }}') center/cover no-repeat;"></div>
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
</main>

@push('scripts')
<script>
    (function(){
        const buttons = document.querySelectorAll('#categoryFilter .category-card');
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
    .category-list .category-item {
        border: 1px solid #eaeaea;
        border-radius: 12px;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: transform 0.12s ease, box-shadow 0.12s ease, border-color 0.12s ease;
    }
    .category-list .category-item.active {
        border-color: #198754;
        box-shadow: 0 8px 18px rgba(25,135,84,0.2);
        transform: translateY(-1px);
    }
    .category-list .category-item:not(.active):hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(0,0,0,0.12);
    }
    .category-thumb {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        background-size: cover;
        background-position: center;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.35), 0 4px 10px rgba(0,0,0,0.12);
        flex-shrink: 0;
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
</style>
@endpush
@endsection

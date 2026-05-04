@extends('layouts.website')

@section('content')
@if($gameAdvertisements->isNotEmpty())
<section class="ad-marquee ad-marquee-games" aria-label="Jogos em destaque">
    <div class="ad-marquee-track">
        @foreach($gameAdvertisements->concat($gameAdvertisements) as $advertisement)
            @php($tag = $advertisement->link_url ? 'a' : 'div')
            <{{ $tag }} class="game-ad-item" @if($advertisement->link_url) href="{{ $advertisement->link_url }}" target="_blank" rel="noopener" @endif>
                <span class="game-ad-dot" aria-hidden="true"></span>
                <span class="game-ad-title">{{ $advertisement->title }}</span>
                @if($advertisement->draw_date)
                    <span class="game-ad-date">Sorteio {{ $advertisement->draw_date->format('d-m-Y') }}</span>
                @endif
                @if($advertisement->subtitle)
                    <span class="game-ad-subtitle">{{ $advertisement->subtitle }}</span>
                @endif
                @if($advertisement->link_url)
                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                @endif
            </{{ $tag }}>
        @endforeach
    </div>
</section>
@endif

<!-- Hero Section -->
<header class="hero">
    <div class="container">
        <h1 class="display-5 fw-bold">
            Bem-vindo à nossa missão de ajuda a quem precisa
        </h1>
        <p class="lead">
            O seu donativo faz a diferença na vida de quem precisa. Juntos
            promovemos a inclusão e a melhoria da qualidade de vida.
        <br>
            E ainda se habilita a um sorteio no final.
        </p>
        <a href="/donativo" class="btn btn-success btn-lg">Fazer um Donativo</a>
    </div>
</header>

@if($sponsorAdvertisements->isNotEmpty())
<section class="ad-marquee ad-marquee-sponsors" aria-label="Sponsors">
    <div class="ad-marquee-track ad-marquee-track-slow">
        @foreach($sponsorAdvertisements->concat($sponsorAdvertisements) as $advertisement)
            @php($tag = $advertisement->link_url ? 'a' : 'div')
            <{{ $tag }} class="sponsor-ad-item" @if($advertisement->link_url) href="{{ $advertisement->link_url }}" target="_blank" rel="noopener" @endif>
                @if($advertisement->logo)
                    <img src="{{ $advertisement->logo->preview }}" alt="{{ $advertisement->title }}">
                @else
                    <span class="sponsor-placeholder" aria-label="{{ $advertisement->title }}">
                        <i class="bi bi-building" aria-hidden="true"></i>
                        <span>{{ $advertisement->title }}</span>
                    </span>
                @endif
            </{{ $tag }}>
        @endforeach
    </div>
</section>
@endif

<!-- Project Description -->
<section class="py-5">
    <div class="container">
        <h2 class="text-success mb-3">O Projeto</h2>
        <p>
            Esta plataforma foi criada para apoiar iniciativas de apoio humanitário
            e inclusão social. Aqui poderá saber mais sobre as ações que
            desenvolvemos, os projetos em curso e como os seus contributos são
            utilizados. Utilize este espaço para explicar de forma detalhada a
            missão da organização, apresentar histórias de impacto e inspirar
            visitantes a juntarem-se a esta causa.
        </p>
        <p>
            Pode editar este texto conforme necessário, adicionando estatísticas,
            gráficos ou imagens que reforcem a importância do projeto. O objetivo
            é transmitir confiança e transparência aos potenciais doadores.
        </p>
    </div>
</section>
@endsection

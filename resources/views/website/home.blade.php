@extends('layouts.website')

@section('content')
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

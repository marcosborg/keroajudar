@extends('layouts.website')
@php use Illuminate\Support\Str; @endphp

@section('content')
<main class="beneficiary-page">
    <section class="beneficiary-hero" style="background-image: linear-gradient(180deg, rgba(15,23,42,0.25), rgba(15,23,42,0.75)), url('{{ $beneficiary->cover_url }}');">
        <div class="container">
            <div class="hero-card">
                <div class="hero-logo">
                    @if($beneficiary->logo_square)
                        <img src="{{ $beneficiary->logo_square->preview ?? $beneficiary->logo_square->url }}" alt="Logo {{ $beneficiary->name }}">
                    @else
                        <span class="hero-logo__fallback">{{ Str::upper(Str::substr($beneficiary->name, 0, 2)) }}</span>
                    @endif
                </div>
                <div class="hero-info">
                    <p class="hero-tag">{{ $beneficiary->category->name ?? 'Beneficiario' }}</p>
                    <h1>{{ $beneficiary->name }}</h1>
                    @if($beneficiary->description)
                        <p class="hero-description">{{ $beneficiary->description }}</p>
                    @endif
                    <div class="hero-meta">
                        @if($beneficiary->city || $beneficiary->country)
                            <span><i class="bi bi-geo-alt-fill"></i> {{ trim(($beneficiary->city ?? '') . ' ' . ($beneficiary->country ?? '')) }}</span>
                        @endif
                        @if($beneficiary->website)
                            <span><i class="bi bi-globe2"></i> {{ $beneficiary->website }}</span>
                        @endif
                        @if($beneficiary->contact_phone)
                            <span><i class="bi bi-telephone-fill"></i> {{ $beneficiary->contact_phone }}</span>
                        @endif
                    </div>
                    <div class="hero-actions">
                        <a class="btn btn-success" href="#donationForm">Fazer Donativo</a>
                        <button class="btn btn-outline-light" type="button" onclick="navigator.clipboard.writeText('{{ $shareUrl }}')">Partilhar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="beneficiary-body">
        <div class="container">
            @if(session('donation'))
                <div class="donation-success">
                    <div class="donation-success__ticket">
                        <div>
                            <p class="text-uppercase text-success fw-semibold mb-1">Donativo confirmado</p>
                            <h3 class="mb-1">Obrigado pelo seu apoio!</h3>
                            <p class="text-muted mb-0">Transacao: {{ session('donation.transaction') }}</p>
                            @if(!session('donation.game_active'))
                                <p class="text-muted mb-0">Nao ha sorteio ativo no momento. O donativo foi registado sem numeros.</p>
                            @endif
                        </div>
                        @if(session('donation.game_active'))
                            <div class="ticket-code">
                                <span class="ticket-label">Codigo do sorteio</span>
                                <span class="ticket-number">{{ session('donation.numbers.0') }}</span>
                            </div>
                        @endif
                    </div>
                    @if(count(session('donation.numbers', [])) > 1)
                        <div class="ticket-numbers">
                            @foreach(session('donation.numbers') as $number)
                                <span>{{ $number }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <p class="stat-label">Total de donativos</p>
                            <h4>{{ $stats['donations'] }}</h4>
                        </div>
                        <div class="stat-card">
                            <p class="stat-label">Valor angariado</p>
                            <h4>{{ number_format($stats['total_amount'], 2, ',', '.') }} &euro;</h4>
                        </div>
                        <div class="stat-card">
                            <p class="stat-label">Numeros emitidos</p>
                            <h4>{{ $stats['numbers'] }}</h4>
                        </div>
                    </div>

                    <div class="content-card">
                        <h3>Sobre a causa</h3>
                        <p>{{ $beneficiary->about ?: 'Esta causa dedica-se a criar impacto real na comunidade, com projetos continuos e acompanhamento proximo.' }}</p>
                    </div>

                    <div class="content-card">
                        <div class="section-header">
                            <h3>Impacto</h3>
                            <span class="badge bg-success">Em progresso</span>
                        </div>
                        <div class="impact-grid">
                            <div>
                                <h5>Projetos concluídos</h5>
                                <p class="text-muted">Apoio a familias locais, melhoria de infraestruturas e apoio veterinario.</p>
                            </div>
                            <div>
                                <h5>Impacto imediato</h5>
                                <p class="text-muted">Cada donativo ajuda a garantir recursos basicos e acompanhamento especializado.</p>
                            </div>
                            <div>
                                <h5>Proximos passos</h5>
                                <p class="text-muted">Expansao da rede de apoio e novas campanhas de sensibilizacao.</p>
                            </div>
                        </div>
                    </div>

                    <div class="content-card">
                        <h3>Historias</h3>
                        <div class="stories-grid">
                            <div class="story-card">
                                <h6>Uma ajuda que chegou a tempo</h6>
                                <p class="text-muted">Uma familia recebeu apoio essencial durante uma fase critica.</p>
                            </div>
                            <div class="story-card">
                                <h6>Mais oportunidades</h6>
                                <p class="text-muted">Equipamentos novos permitiram continuar um projeto comunitario.</p>
                            </div>
                        </div>
                    </div>

                    <div class="content-card">
                        <h3>Transparencia</h3>
                        <ul class="transparency-list">
                            <li><strong>Relatorios regulares:</strong> atualizamos os resultados das campanhas.</li>
                            <li><strong>Equipa local:</strong> acompanhamento direto das necessidades.</li>
                            <li><strong>Objetivos claros:</strong> cada campanha tem metas definidas.</li>
                        </ul>
                    </div>

                    <div class="content-card">
                        <h3>FAQ</h3>
                        <div class="faq-item">
                            <h6>Como funciona o sorteio?</h6>
                            <p class="text-muted">Depois de doar, recebe numeros de participacao para o sorteio.</p>
                        </div>
                        <div class="faq-item">
                            <h6>Como sei que o donativo chegou?</h6>
                            <p class="text-muted">Recebe confirmacao e o codigo do sorteio apos o pagamento.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="donation-card" id="donationForm">
                        <h3>Fazer Donativo</h3>
                        <p class="text-muted">Escolha um valor e receba numeros para o sorteio.</p>
                        @if(!$activeGame)
                            <div class="alert alert-light border">
                                Nao ha sorteio ativo no momento. O seu donativo sera registado sem numeros.
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('website.beneficiary.donate.store', ['beneficiary' => $beneficiary->id, 'slug' => Str::slug($beneficiary->name)]) }}" id="donationFormElement">
                            @csrf
                            <div class="amount-picker">
                                <label class="form-label">Valor do donativo</label>
                                <div class="amount-quick">
                                    @foreach([5,10,25,50] as $quick)
                                        <button type="button" class="amount-chip" data-value="{{ $quick }}">{{ $quick }}&euro;</button>
                                    @endforeach
                                </div>
                                <input type="range" min="5" max="250" step="1" value="{{ old('amount', 25) }}" id="amountRange" class="form-range">
                                <div class="amount-input">
                                    <input type="number" name="amount" id="amountInput" class="form-control" min="1" step="1" value="{{ old('amount', 25) }}" required>
                                    <span class="amount-currency">&euro;</span>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label">Nome</label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Apelido</label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Telefone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Cidade</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">NIF (opcional)</label>
                                    <input type="text" name="nif" class="form-control" value="{{ old('nif') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">NIPC (empresa)</label>
                                    <input type="text" name="nipc" class="form-control" value="{{ old('nipc') }}">
                                </div>
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="consent_privacy" id="consentPrivacy" required {{ old('consent_privacy') ? 'checked' : '' }}>
                                <label class="form-check-label" for="consentPrivacy">
                                    Aceito a politica de privacidade.
                                </label>
                            </div>

                            <button type="button" class="btn btn-success w-100 mt-3" id="openSandbox">Continuar pagamento</button>
                        </form>
                    </div>

                    <div class="raffle-card" id="raffleCard"
                         @if($activeGame)
                             data-start="{{ $activeGame->starts_at?->timestamp }}"
                             data-end="{{ $activeGame->ends_at?->timestamp }}"
                         @endif>
                        <div class="raffle-header">
                            <h4>Jogo atual</h4>
                            @if($activeGame)
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-secondary">Encerrado</span>
                            @endif
                        </div>
                        @if($activeGame)
                            <div class="raffle-main">
                                <h5 class="mb-1">{{ $activeGame->name }}</h5>
                                @if($activeGame->prize)
                                    <p class="text-muted mb-0">Premio: {{ $activeGame->prize->name }}</p>
                                @endif
                                <p class="text-muted mb-0">De {{ $activeGame->starts_at?->format('d/m/Y') }} ate {{ $activeGame->ends_at?->format('d/m/Y') }}</p>
                            </div>
                            <div class="raffle-progress">
                                <div class="raffle-progress__bar">
                                    <span id="raffleProgress"></span>
                                </div>
                                <div class="raffle-progress__meta">
                                    <span>Progresso</span>
                                    <strong id="raffleProgressLabel">0%</strong>
                                </div>
                            </div>
                            <div class="raffle-countdown" id="raffleCountdown">A terminar em...</div>
                            <div class="raffle-rules">
                                <p class="text-muted mb-2">Regras do jogo</p>
                                <ul>
                                    @forelse($rules as $rule)
                                        <li>{{ number_format($rule->amount, 2, ',', '.') }} &euro; =&gt; {{ $rule->numbers }} numero(s)</li>
                                    @empty
                                        <li>Regra base: 1 numero por donativo.</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="raffle-highlight">
                                <span>Intervalo</span>
                                <strong>10000 - 999999</strong>
                            </div>
                        @else
                            <p class="text-muted mb-0">Nao ha sorteio ativo no momento. Pode doar e apoiar a causa.</p>
                        @endif
                    </div>

                    <div class="contact-card">
                        <h4>Contacto</h4>
                        <p class="text-muted mb-2">Fale diretamente com o beneficiario.</p>
                        <ul>
                            @if($beneficiary->contact_email)
                                <li><i class="bi bi-envelope"></i> {{ $beneficiary->contact_email }}</li>
                            @endif
                            @if($beneficiary->contact_phone)
                                <li><i class="bi bi-telephone"></i> {{ $beneficiary->contact_phone }}</li>
                            @endif
                            @if($beneficiary->website)
                                <li><i class="bi bi-globe"></i> {{ $beneficiary->website }}</li>
                            @endif
                            @if($beneficiary->address)
                                <li><i class="bi bi-geo"></i> {{ $beneficiary->address }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<div class="sandbox-modal" id="sandboxModal" aria-hidden="true">
    <div class="sandbox-modal__card">
        <button class="sandbox-close" type="button" id="closeSandbox">&times;</button>
        <h3>Pagamento Sandbox</h3>
        <p class="text-muted">Simulacao de pagamento para demonstracao.</p>
        <div class="sandbox-summary">
            <div>
                <span class="text-muted">Beneficiario</span>
                <strong>{{ $beneficiary->name }}</strong>
            </div>
            <div>
                <span class="text-muted">Valor</span>
                <strong id="sandboxAmount">25 &euro;</strong>
            </div>
        </div>
        <p class="text-muted small mb-0">Ao confirmar, o sistema gera os numeros de participacao.</p>
        <button class="btn btn-success w-100 mt-3" id="confirmSandbox">Confirmar pagamento</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const amountInput = document.getElementById('amountInput');
        const amountRange = document.getElementById('amountRange');
        const chips = document.querySelectorAll('.amount-chip');
        const modal = document.getElementById('sandboxModal');
        const openBtn = document.getElementById('openSandbox');
        const closeBtn = document.getElementById('closeSandbox');
        const confirmBtn = document.getElementById('confirmSandbox');
        const sandboxAmount = document.getElementById('sandboxAmount');
        const form = document.getElementById('donationFormElement');

        function syncAmount(value) {
            amountInput.value = value;
            amountRange.value = value;
            sandboxAmount.textContent = value + ' \u20ac';
            chips.forEach(chip => {
                chip.classList.toggle('active', chip.dataset.value === String(value));
            });
        }

        chips.forEach(chip => {
            chip.addEventListener('click', () => syncAmount(chip.dataset.value));
        });

        amountRange.addEventListener('input', () => syncAmount(amountRange.value));
        amountInput.addEventListener('input', () => syncAmount(amountInput.value || 0));

        openBtn.addEventListener('click', () => {
            syncAmount(amountInput.value || amountRange.value);
            modal.classList.add('is-open');
        });

        closeBtn.addEventListener('click', () => modal.classList.remove('is-open'));
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.remove('is-open');
            }
        });

        confirmBtn.addEventListener('click', () => form.submit());

        syncAmount(amountInput.value || amountRange.value);

        const raffleCard = document.getElementById('raffleCard');
        const progressBar = document.getElementById('raffleProgress');
        const progressLabel = document.getElementById('raffleProgressLabel');
        const countdown = document.getElementById('raffleCountdown');

        if (raffleCard && raffleCard.dataset.start && raffleCard.dataset.end) {
            const start = Number(raffleCard.dataset.start) * 1000;
            const end = Number(raffleCard.dataset.end) * 1000;

            const updateCountdown = () => {
                const now = Date.now();
                const total = Math.max(1, end - start);
                const elapsed = Math.min(total, Math.max(0, now - start));
                const percent = Math.round((elapsed / total) * 100);
                if (progressBar) {
                    progressBar.style.width = percent + '%';
                }
                if (progressLabel) {
                    progressLabel.textContent = percent + '%';
                }

                const remaining = Math.max(0, end - now);
                if (countdown) {
                    if (remaining <= 0) {
                        countdown.textContent = 'Jogo encerrado';
                        return;
                    }
                    const days = Math.floor(remaining / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((remaining / (1000 * 60 * 60)) % 24);
                    const minutes = Math.floor((remaining / (1000 * 60)) % 60);
                    countdown.textContent = 'Termina em ' + days + 'd ' + hours + 'h ' + minutes + 'm';
                }
            };

            updateCountdown();
            setInterval(updateCountdown, 60000);
        }
    })();
</script>
@endpush

@push('styles')
<style>
    .beneficiary-page {
        background: #f6fbf8;
    }
    .beneficiary-hero {
        color: #fff;
        padding: 3.5rem 0 4rem;
        background-size: cover;
        background-position: center;
    }
    .hero-card {
        display: flex;
        gap: 24px;
        align-items: center;
        background: rgba(15, 23, 42, 0.55);
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.3);
        backdrop-filter: blur(4px);
    }
    .hero-logo {
        width: 96px;
        height: 96px;
        border-radius: 24px;
        overflow: hidden;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .hero-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .hero-logo__fallback {
        font-weight: 700;
        color: #198754;
        font-size: 1.5rem;
    }
    .hero-tag {
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.08em;
        color: #d4f8e5;
        margin-bottom: 0.35rem;
    }
    .hero-info h1 {
        font-size: clamp(2rem, 3vw, 2.8rem);
        margin-bottom: 0.5rem;
    }
    .hero-description {
        max-width: 640px;
        margin-bottom: 1rem;
        color: rgba(255, 255, 255, 0.85);
    }
    .hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        font-size: 0.95rem;
        margin-bottom: 1.25rem;
    }
    .hero-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .hero-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .beneficiary-body {
        padding: 2.5rem 0 4rem;
    }
    .donation-success {
        background: #fff;
        border: 1px solid #dfe9e3;
        border-radius: 18px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
    }
    .donation-success__ticket {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .ticket-code {
        background: #e9f7f0;
        padding: 12px 16px;
        border-radius: 14px;
        text-align: center;
    }
    .ticket-label {
        display: block;
        text-transform: uppercase;
        font-size: 0.7rem;
        color: #2f5f45;
        letter-spacing: 0.08em;
    }
    .ticket-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #198754;
    }
    .ticket-numbers {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 16px;
    }
    .ticket-numbers span {
        background: #f1f5f9;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.85rem;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
    }
    .stat-label {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .content-card {
        background: #fff;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
        margin-bottom: 20px;
    }
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
    }
    .impact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    .stories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }
    .story-card {
        background: #f8fafc;
        border-radius: 14px;
        padding: 14px;
    }
    .transparency-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    .transparency-list li {
        padding: 8px 0;
        border-bottom: 1px solid #eef2f1;
    }
    .transparency-list li:last-child {
        border-bottom: 0;
    }
    .faq-item + .faq-item {
        margin-top: 12px;
    }
    .donation-card,
    .raffle-card,
    .contact-card {
        background: #fff;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
        margin-bottom: 20px;
    }
    .amount-quick {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
    }
    .amount-chip {
        border: 1px solid #dfe9e3;
        background: #fff;
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 600;
        color: #334155;
        transition: all 0.15s ease;
    }
    .amount-chip.active,
    .amount-chip:hover {
        background: #198754;
        color: #fff;
        border-color: #198754;
        transform: translateY(-1px);
    }
    .amount-input {
        position: relative;
    }
    .amount-input input {
        padding-right: 48px;
    }
    .amount-currency {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
    }
    .raffle-card ul {
        padding-left: 18px;
    }
    .raffle-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }
    .raffle-main {
        margin-bottom: 12px;
    }
    .raffle-progress {
        margin: 12px 0;
    }
    .raffle-progress__bar {
        height: 8px;
        background: #e2e8f0;
        border-radius: 999px;
        overflow: hidden;
    }
    .raffle-progress__bar span {
        display: block;
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #198754, #20c997);
    }
    .raffle-progress__meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 6px;
    }
    .raffle-countdown {
        font-weight: 600;
        color: #198754;
        margin-bottom: 12px;
    }
    .raffle-highlight {
        margin-top: 16px;
        padding: 12px;
        border-radius: 12px;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        font-weight: 600;
    }
    .contact-card ul {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    .contact-card li {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 0;
        color: #334155;
    }
    .sandbox-modal {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
        z-index: 1055;
    }
    .sandbox-modal.is-open {
        opacity: 1;
        visibility: visible;
    }
    .sandbox-modal__card {
        background: #fff;
        border-radius: 18px;
        padding: 24px;
        width: min(420px, 100%);
        position: relative;
        box-shadow: 0 18px 36px rgba(15, 23, 42, 0.25);
    }
    .sandbox-close {
        position: absolute;
        top: 12px;
        right: 14px;
        border: 0;
        background: transparent;
        font-size: 1.5rem;
        color: #94a3b8;
    }
    .sandbox-summary {
        display: grid;
        gap: 12px;
        margin: 16px 0;
        padding: 12px;
        border-radius: 12px;
        background: #f8fafc;
    }
    @media (max-width: 992px) {
        .hero-card {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

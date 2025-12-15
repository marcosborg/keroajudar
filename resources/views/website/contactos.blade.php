@extends('layouts.website')

@section('content')
<!-- Content -->
<main class="py-5">
    <div class="container">
        <h1 class="text-success mb-4">Contactos</h1>
        <p>
            Entre em contacto connosco para obter mais informações sobre os nossos
            projetos ou para esclarecer quaisquer dúvidas. Pode utilizar o
            formulário abaixo ou recorrer aos contactos diretos fornecidos.
        </p>
        <div class="row">
            <div class="col-md-6 mb-4">
                <h4 class="mb-3">Informações de contacto</h4>
                <p class="mb-1"><strong>Morada:</strong> Rua Exemplo, 123, 4000-123 Porto</p>
                <p class="mb-1"><strong>Email:</strong> <a href="mailto:info@example.com">info@example.com</a></p>
                <p class="mb-1"><strong>Telefone:</strong> 21 234 5678</p>
            </div>
            <div class="col-md-6">
                <h4 class="mb-3">Formulário de contacto</h4>
                <form id="contactForm">
                    <div class="mb-3">
                        <label for="contactName" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="contactName" name="contactName" required />
                    </div>
                    <div class="mb-3">
                        <label for="contactEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="contactEmail" name="contactEmail" required />
                    </div>
                    <div class="mb-3">
                        <label for="contactMessage" class="form-label">Mensagem</label>
                        <textarea class="form-control" id="contactMessage" name="contactMessage" rows="5" required></textarea>
                    </div>
                    @if(config('services.recaptcha.site_key'))
                        <div class="mb-3">
                            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        </div>
                    @else
                        <div class="alert alert-warning" role="alert">
                            Configure a sua chave do reCAPTCHA em RECAPTCHA_SITE_KEY para ativar a validação anti-spam.
                        </div>
                    @endif
                    <button type="submit" class="btn btn-success">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
@if(config('services.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    (function() {
        const form = document.getElementById('contactForm');
        if (!form) return;

        form.addEventListener('submit', function(event) {
            const captchaResponse = form.querySelector('.g-recaptcha-response');
            if (!captchaResponse || !captchaResponse.value) {
                event.preventDefault();
                alert('Por favor, confirme que não é um robô.');
            }
        });
    })();
</script>
@endif
@endpush

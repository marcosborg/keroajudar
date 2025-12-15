<!DOCTYPE html>
<html lang="pt">
<head>
    <!-- Consent Mode default (v2) -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('consent', 'default', {
            ad_user_data: 'denied',
            ad_personalization: 'denied',
            ad_storage: 'denied',
            analytics_storage: 'denied',
            functionality_storage: 'granted',
            personalization_storage: 'denied',
            security_storage: 'granted',
            wait_for_update: 500
        });
    </script>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-P5JGM585');</script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kero Ajudar Â· Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('website/styles.css') }}?v=20251205" />
</head>
<body class="@yield('body-class')">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P5JGM585"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div id="consent-banner" class="consent-banner d-none">
        <div class="consent-content">
            <div class="consent-text">
                <strong>Privacidade</strong>
                <p class="mb-0">Usamos cookies para melhorar a experiÃªncia e medir trÃ¡fego. Pode aceitar ou recusar.</p>
            </div>
            <div class="consent-actions">
                <button type="button" class="btn btn-outline-light btn-sm" id="consent-reject">Recusar</button>
                <button type="button" class="btn btn-light btn-sm" id="consent-accept">Aceitar</button>
            </div>
        </div>
    </div>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/" aria-label="Kero Ajudar">
                <span class="brand-icon" aria-hidden="true"></span>
                <span class="fw-bold">Kero Ajudar</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item"><a class="nav-link active" href="/">InÃ­cio</a></li>
                    <li class="nav-item"><a class="nav-link" href="/donativo">Donativo</a></li>
                    <li class="nav-item"><a class="nav-link" href="/beneficiarios">Beneficiarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="/quem-somos">Quem Somos</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contactos">Contactos</a></li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-1" href="{{ route('admin.home') }}">
                                <i class="bi bi-speedometer2" aria-hidden="true"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link d-flex align-items-center gap-1 p-0">
                                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i> <span>Sair</span>
                                </button>
                            </form>
                        </li>
                    @endauth
                    @guest
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-1" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> <span>Login</span>
                            </a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>


    @yield('content')

    <!-- Footer -->
    <footer class="bg-success text-white text-center py-3 mt-auto">
        <div class="container">
            <p class="mb-0">
                &copy; 2025 Kero ajudar. Todos os direitos reservados.
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .consent-banner {
            position: fixed;
            inset: auto 0 0 0;
            z-index: 1050;
            background: rgba(25, 135, 84, 0.95);
            color: #fff;
            padding: 14px 18px;
            box-shadow: 0 -4px 12px rgba(0,0,0,0.2);
        }
        .consent-banner .consent-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .consent-banner .consent-text p {
            margin: 4px 0 0 0;
            opacity: 0.95;
        }
        .consent-banner .consent-actions {
            display: flex;
            gap: 8px;
        }
        @media (max-width: 576px) {
            .consent-banner .consent-content {
                flex-direction: column;
                align-items: flex-start;
            }
            .consent-banner .consent-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
    <script>
        (function() {
            const banner = document.getElementById('consent-banner');
            if (!banner) return;

            const STORAGE_KEY = 'consent_prefs_v1';

            function applyConsent(consent) {
                if (typeof gtag !== 'function') return;
                gtag('consent', 'update', consent);
            }

            function save(choice) {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(choice));
            }

            function load() {
                try {
                    const raw = localStorage.getItem(STORAGE_KEY);
                    return raw ? JSON.parse(raw) : null;
                } catch (_) {
                    return null;
                }
            }

            const stored = load();
            if (stored) {
                applyConsent(stored);
                return;
            }

            banner.classList.remove('d-none');

            document.getElementById('consent-accept').addEventListener('click', function() {
                const granted = {
                    ad_user_data: 'granted',
                    ad_personalization: 'granted',
                    ad_storage: 'granted',
                    analytics_storage: 'granted',
                    functionality_storage: 'granted',
                    personalization_storage: 'granted',
                    security_storage: 'granted'
                };
                applyConsent(granted);
                save(granted);
                banner.remove();
            });

            document.getElementById('consent-reject').addEventListener('click', function() {
                const denied = {
                    ad_user_data: 'denied',
                    ad_personalization: 'denied',
                    ad_storage: 'denied',
                    analytics_storage: 'denied',
                    functionality_storage: 'granted',
                    personalization_storage: 'denied',
                    security_storage: 'granted'
                };
                applyConsent(denied);
                save(denied);
                banner.remove();
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>

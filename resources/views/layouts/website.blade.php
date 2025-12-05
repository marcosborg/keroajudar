<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kero Ajudar · Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('website/styles.css') }}?v=20251205" />
</head>
<body class="@yield('body-class')">
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
                    <li class="nav-item"><a class="nav-link active" href="/">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="/donativo">Donativo</a></li>
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
</body>
</html>

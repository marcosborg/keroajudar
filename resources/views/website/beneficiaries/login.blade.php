@extends('layouts.website')

@section('content')
<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <p class="text-uppercase text-success fw-semibold mb-1">Beneficiários</p>
                        <h2 class="mb-3">Login</h2>
                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('beneficiaries.login.store') }}" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <a href="{{ route('beneficiaries.register') }}" class="text-success">Criar conta</a>
                                    <a href="{{ route('beneficiaries.password.request') }}" class="text-success small">Esqueci a password</a>
                                </div>
                                <button type="submit" class="btn btn-success">Entrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

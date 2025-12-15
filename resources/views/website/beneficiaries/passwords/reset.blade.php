@extends('layouts.website')

@section('content')
<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <p class="text-uppercase text-success fw-semibold mb-1">Beneficiários</p>
                        <h2 class="mb-3">Definir nova password</h2>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('beneficiaries.password.update') }}" class="row g-3">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Nova password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Confirmar password</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <a href="{{ route('beneficiaries.login') }}" class="text-success">Voltar ao login</a>
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

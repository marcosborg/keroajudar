@extends('layouts.website')

@section('content')
<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <p class="text-uppercase text-success fw-semibold mb-1">Beneficiários</p>
                        <h2 class="mb-3">Recuperar password</h2>
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
                        <form method="POST" action="{{ route('beneficiaries.password.email') }}" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <a href="{{ route('beneficiaries.login') }}" class="text-success">Voltar ao login</a>
                                <button type="submit" class="btn btn-success">Enviar link de reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

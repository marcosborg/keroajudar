@extends('layouts.website')

@section('content')
<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <p class="text-uppercase text-success fw-semibold mb-1">Beneficiários</p>
                        <h2 class="mb-3">Criar conta</h2>
                        <p class="text-muted mb-4">Após submeter, a conta fica pendente até aprovação. Receberá aviso quando estiver ativa.</p>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('beneficiaries.register.store') }}" class="row g-3">
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label">Categoria *</label>
                                <select name="beneficiary_category_id" class="form-select" required>
                                    <option value="">Selecione</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('beneficiary_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nome da entidade *</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email (login) *</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">NIPC *</label>
                                <input type="text" class="form-control" name="vat_number" value="{{ old('vat_number') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cód. Certidão Comercial *</label>
                                <input type="text" class="form-control" name="commercial_certificate_code" value="{{ old('commercial_certificate_code') }}" required placeholder="0000-0000-0000">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">IBAN *</label>
                                <input type="text" class="form-control" name="iban" value="{{ old('iban') }}" required placeholder="PT50...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telefone</label>
                                <input type="text" class="form-control" name="contact_phone" value="{{ old('contact_phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Website</label>
                                <input type="text" class="form-control" name="website" value="{{ old('website') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Morada</label>
                                <input type="text" class="form-control" name="address" value="{{ old('address') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Código Postal *</label>
                                <input type="text" class="form-control" name="postal_code" value="{{ old('postal_code') }}" required placeholder="0000-000">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Cidade</label>
                                <input type="text" class="form-control" name="city" value="{{ old('city') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">País</label>
                                <input type="text" class="form-control" name="country" value="{{ old('country') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password *</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirmar password *</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <a href="{{ route('beneficiaries.login') }}" class="text-success">Já tenho conta</a>
                                <button type="submit" class="btn btn-success">Submeter para aprovação</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

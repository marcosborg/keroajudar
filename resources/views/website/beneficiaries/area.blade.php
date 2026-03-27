@extends('layouts.website')

@section('content')
<main class="py-5">
    <div class="container">
        <div class="position-relative rounded-4 overflow-hidden shadow-sm mb-4" style="min-height: 220px; background: linear-gradient(180deg, rgba(0,0,0,0.3), rgba(0,0,0,0.55)), url('{{ $beneficiary->cover_url }}') center/cover no-repeat;">
            <div class="position-absolute top-0 start-0 end-0 bottom-0 d-flex align-items-end p-4">
                <div class="d-flex align-items-center gap-3 bg-dark bg-opacity-50 text-white p-3 rounded-3">
                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center" style="width:70px;height:70px;overflow:hidden;">
                        @if($beneficiary->logo_square)
                            <img src="{{ $beneficiary->logo_square->thumbnail }}" alt="Logo {{ $beneficiary->name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <span class="text-success fw-bold">KA</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-uppercase small mb-1">Área do beneficiário</p>
                        <h3 class="mb-0">{{ $beneficiary->name }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <p class="text-uppercase text-success fw-semibold mb-1">Minha conta</p>
                        <h4 class="mb-3">{{ $beneficiary->name }}</h4>
                        <p class="mb-1"><strong>Estado:</strong> <span class="badge bg-{{ $beneficiary->active && $beneficiary->approved_at ? 'success' : 'secondary' }}">{{ $beneficiary->active && $beneficiary->approved_at ? 'Ativa' : 'Pendente' }}</span></p>
                        @if($beneficiary->approved_at)
                            <p class="text-muted small mb-1">Aprovado em {{ $beneficiary->approved_at->format('d/m/Y H:i') }}</p>
                        @endif
                        @if($beneficiary->last_login_at)
                            <p class="text-muted small mb-3">Último acesso: {{ $beneficiary->last_login_at->format('d/m/Y H:i') }}</p>
                        @endif
                        <div class="mb-3">
                            <label class="form-label small text-uppercase text-muted">Link para partilhar</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="shareLink" value="{{ $shareUrl }}" readonly>
                                <button class="btn btn-outline-success" type="button" onclick="navigator.clipboard.writeText(document.getElementById('shareLink').value)">Copiar</button>
                            </div>
                            <small class="text-muted">Envie este link para que contribuam diretamente para si.</small>
                        </div>
                        <form method="POST" action="{{ route('beneficiaries.logout') }}">
                            @csrf
                            <button class="btn btn-outline-secondary w-100" type="submit">Terminar sessão</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <h4 class="mb-3">Atualizar dados</h4>
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
                        <form method="POST" action="{{ route('beneficiaries.area.update') }}" class="row g-3" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label">Categoria *</label>
                                <select name="beneficiary_category_id" class="form-select" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $beneficiary->beneficiary_category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nome *</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $beneficiary->name) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descricao</label>
                                <textarea class="form-control" name="description" rows="3">{{ old('description', $beneficiary->description) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email (login) *</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', $beneficiary->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email de contacto</label>
                                <input type="email" class="form-control" name="contact_email" value="{{ old('contact_email', $beneficiary->contact_email) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">NIPC *</label>
                                <input type="text" class="form-control" name="vat_number" value="{{ old('vat_number', $beneficiary->vat_number) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cód. Certidão Comercial *</label>
                                <input type="text" class="form-control" name="commercial_certificate_code" value="{{ old('commercial_certificate_code', $beneficiary->commercial_certificate_code) }}" required placeholder="0000-0000-0000">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">IBAN *</label>
                                <input type="text" class="form-control" name="iban" value="{{ old('iban', $beneficiary->iban) }}" required placeholder="PT50...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telefone</label>
                                <input type="text" class="form-control" name="contact_phone" value="{{ old('contact_phone', $beneficiary->contact_phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Website</label>
                                <input type="text" class="form-control" name="website" value="{{ old('website', $beneficiary->website) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Morada</label>
                                <input type="text" class="form-control" name="address" value="{{ old('address', $beneficiary->address) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Código Postal *</label>
                                <input type="text" class="form-control" name="postal_code" value="{{ old('postal_code', $beneficiary->postal_code) }}" required placeholder="0000-000">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Cidade</label>
                                <input type="text" class="form-control" name="city" value="{{ old('city', $beneficiary->city) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">País</label>
                                <input type="text" class="form-control" name="country" value="{{ old('country', $beneficiary->country) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Sobre</label>
                                <textarea class="form-control" name="about" rows="4">{{ old('about', $beneficiary->about) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Foto/Capa</label>
                                @if($beneficiary->photo)
                                    <div class="mb-2">
                                        <img src="{{ $beneficiary->photo->preview ?? $beneficiary->photo->url }}" alt="Foto atual" class="img-fluid rounded">
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="photo" accept="image/*">
                                <small class="text-muted">Formato imagem, máx. 5MB.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Logo</label>
                                @if($beneficiary->logo_square)
                                    <div class="mb-2" style="width:120px;">
                                        <img src="{{ $beneficiary->logo_square->thumbnail ?? $beneficiary->logo_square->url }}" alt="Logo atual" class="img-fluid rounded">
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="logo_square" accept="image/*">
                                <small class="text-muted">Formato imagem, máx. 5MB.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password (deixar em branco para manter)</label>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirmar password</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">Guardar alterações</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

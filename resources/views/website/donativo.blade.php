@extends('layouts.website')
@php use Illuminate\Support\Str; @endphp

@section('body-class', 'donation-bg')

@section('content')
<main class="py-5 donation-bg">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-8">
        <div class="form-shell">
          <h1 class="mb-1" style="color:#157347">Faça um Donativo</h1>
          <p class="text-muted mb-4">
            Selecione primeiro a categoria e o beneficiário que quer apoiar. Depois preencha os seus dados para concluir o donativo.
          </p>

          <div class="section-title">Escolha uma categoria</div>
          <div class="row g-3 mb-4" id="categoryGrid">
            @foreach($categories as $category)
              @php
                  $cover = $category->image ? ($category->image->preview ?? $category->image->url) : '/images/banner-ajuda.png';
              @endphp
              <div class="col-md-6">
                <button type="button"
                        class="tile-card w-100 text-start"
                        data-category-id="{{ $category->id }}"
                        data-name="{{ $category->name }}"
                        data-description="{{ $category->description }}"
                        style="background-image: linear-gradient(180deg, rgba(0,0,0,0.45), rgba(0,0,0,0.55)), url('{{ $cover }}');">
                  <div class="tile-content">
                    <h5 class="mb-1">{{ $category->name }}</h5>
                    <p class="mb-0 small text-white-50">{{ Str::limit($category->description, 110) }}</p>
                  </div>
                </button>
              </div>
            @endforeach
          </div>

          <div class="section-title d-none" id="beneficiaryStep">Escolha o beneficiário</div>
          <div class="row g-3 mb-4 d-none" id="beneficiaryGrid"></div>

          <div id="selectedSummary" class="alert alert-success d-none mb-4">
            <strong id="selectedCategoryLabel"></strong>
            <span class="d-inline-block mx-2">/</span>
            <span id="selectedBeneficiaryLabel"></span>
          </div>

          <div id="donationFormWrapper" class="d-none">
            <form id="donationForm" novalidate>
              <input type="hidden" name="category_id" id="category_id">
              <input type="hidden" name="beneficiary_id" id="beneficiary_id">
              <!-- Valor -->
              <div class="section-title">Valor do Donativo *</div>
              <div class="amount-group mb-3" id="amountGroup">
                <label class="amount-chip" data-value="15">
                  <input type="radio" name="donationAmount" id="amount15" value="15" required>
                  15€
                </label>
                <label class="amount-chip" data-value="25">
                  <input type="radio" name="donationAmount" id="amount25" value="25"> 25€
                </label>
                <label class="amount-chip" data-value="50">
                  <input type="radio" name="donationAmount" id="amount50" value="50"> 50€
                </label>
                <label class="amount-chip" data-value="other" id="chipOther">
                  <input type="radio" name="donationAmount" id="amountOther" value="other"> Outro valor
                </label>
              </div>
              <div class="mb-4 d-none" id="otherAmountGroup">
                <div class="section-title" style="margin:0"> </div>
                <div class="compact">
                  <label for="otherAmount" class="form-label m-0">Introduza o valor (€)</label>
                  <input type="number" class="form-control" id="otherAmount" name="otherAmount" min="3" step="1" placeholder="Mínimo 3€" />
                </div>
              </div>

              <!-- Tipo de Doador -->
              <div class="section-title">Tipo de Doador</div>
              <div class="mb-3">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="isCompany" name="isCompany" />
                  <label class="form-check-label" for="isCompany">Sou uma Empresa/Organização</label>
                </div>
              </div>
              <div class="mb-3 d-none" id="companyNameGroup">
                <label for="companyName" class="form-label">Nome da Empresa *</label>
                <input type="text" class="form-control" id="companyName" name="companyName" />
              </div>

              <!-- Dados Pessoais -->
              <div class="section-title">Dados Pessoais</div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="firstName" class="form-label">Nome *</label>
                  <input type="text" class="form-control" id="firstName" name="firstName" required />
                </div>
                <div class="col-md-6 mb-3">
                  <label for="lastName" class="form-label">Apelido *</label>
                  <input type="text" class="form-control" id="lastName" name="lastName" required />
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="email" class="form-label">Email *</label>
                  <input type="email" class="form-control" id="email" name="email" required />
                </div>
                <div class="col-md-6 mb-3">
                  <label for="phone" class="form-label">Telefone *</label>
                  <input type="tel" class="form-control" id="phone" name="phone" minlength="9" required />
                </div>
              </div>

              <!-- Data de Nascimento -->
              <div class="section-title">Data de Nascimento</div>
              <div class="mb-4">
                <div class="row g-2">
                  <div class="col-4 col-md-2">
                    <select class="form-select" id="birthDay" name="birthDay">
                      <option value="">Dia</option>
                    </select>
                  </div>
                  <div class="col-4 col-md-4">
                    <select class="form-select" id="birthMonth" name="birthMonth">
                      <option value="">Mês</option>
                    </select>
                  </div>
                  <div class="col-4 col-md-3">
                    <select class="form-select" id="birthYear" name="birthYear">
                      <option value="">Ano</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Morada -->
              <div class="section-title">Morada</div>
              <div class="mb-3">
                <label for="address" class="form-label">Morada</label>
                <input type="text" class="form-control" id="address" name="address" />
              </div>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="postalCode" class="form-label">Código Postal</label>
                  <input type="text" class="form-control" id="postalCode" name="postalCode" />
                </div>
                <div class="col-md-4 mb-3">
                  <label for="city" class="form-label">Localidade</label>
                  <input type="text" class="form-control" id="city" name="city" />
                </div>
                <div class="col-md-4 mb-3">
                  <label for="country" class="form-label">País</label>
                  <select class="form-select" id="country" name="country">
                    <option value="">Selecione</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Espanha">Espanha</option>
                    <option value="França">França</option>
                    <option value="Alemanha">Alemanha</option>
                    <option value="Brasil">Brasil</option>
                    <option value="Angola">Angola</option>
                    <option value="Moçambique">Moçambique</option>
                    <option value="Cabo Verde">Cabo Verde</option>
                    <option value="Outros">Outros</option>
                  </select>
                </div>
              </div>

              <!-- Fiscais -->
              <div class="section-title">Dados Fiscais</div>
              <div class="mb-3" id="nifGroup">
                <label for="nif" class="form-label">NIF</label>
                <input type="text" class="form-control" id="nif" name="nif" />
              </div>
              <div class="mb-3 d-none" id="nipcGroup">
                <label for="nipc" class="form-label">NIPC</label>
                <input type="text" class="form-control" id="nipc" name="nipc" />
              </div>

              <!-- Privacidade -->
              <div class="section-title">Privacidade</div>
              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="privacyConsent" required />
                  <label class="form-check-label" for="privacyConsent">
                    Confirmo que li e aceito a <a href="#">Política de Privacidade</a> *
                  </label>
                </div>
              </div>

              <!-- Preferências -->
              <div class="section-title">Preferências de Contacto</div>
              <div class="mb-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="infoMail" name="subscribe" value="mail" />
                  <label class="form-check-label" for="infoMail">Correio</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="infoEmail" name="subscribe" value="email" />
                  <label class="form-check-label" for="infoEmail">Email</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="infoPhone" name="subscribe" value="phone" />
                  <label class="form-check-label" for="infoPhone">Telemóvel</label>
                </div>
              </div>

              <button type="submit" class="btn btn-success">Enviar</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Sidebar opcional (podes remover) -->
      <div class="col-lg-4">
        <div class="info-card h-100">
          <h5 class="mb-3">Porque o seu gesto conta</h5>
          <ul class="list-unstyled text-muted">
            <li>• Apoia directamente famílias vulneráveis</li>
            <li>• Transparência e relatório de impacto</li>
            <li>• Benefícios fiscais aplicáveis</li>
          </ul>
          <hr>
          <p class="small text-muted mb-0">Tem dúvidas? <a href="/contactos">Fale connosco</a>.</p>
        </div>
      </div>
    </div>
  </div>
</main>

{{-- JS leve para chips, condicionais e seleção de beneficiários --}}
@push('scripts')
<script>
  (function(){
    const categories = @json(
      $categories->map(fn($c) => [
        'id' => $c->id,
        'name' => $c->name,
        'description' => $c->description,
        'image' => $c->image?->preview ?? $c->image?->url,
        'beneficiaries' => $c->beneficiaries->map(fn($b) => [
          'id' => $b->id,
          'name' => $b->name,
          'description' => $b->description,
          'image' => $b->photo?->preview ?? $b->photo?->url,
        ]),
      ])
    );

    const categoryGrid = document.getElementById('categoryGrid');
    const beneficiaryGrid = document.getElementById('beneficiaryGrid');
    const beneficiaryStep = document.getElementById('beneficiaryStep');
    const donationFormWrapper = document.getElementById('donationFormWrapper');
    const selectedSummary = document.getElementById('selectedSummary');
    const selectedCategoryLabel = document.getElementById('selectedCategoryLabel');
    const selectedBeneficiaryLabel = document.getElementById('selectedBeneficiaryLabel');
    const categoryInput = document.getElementById('category_id');
    const beneficiaryInput = document.getElementById('beneficiary_id');

    categoryGrid?.querySelectorAll('.tile-card').forEach(card => {
      card.addEventListener('click', () => {
        const categoryId = Number(card.dataset.categoryId);
        categoryInput.value = categoryId;
        categoryGrid.querySelectorAll('.tile-card').forEach(c => c.classList.remove('active'));
        card.classList.add('active');
        renderBeneficiaries(categoryId);
        donationFormWrapper.classList.add('d-none');
        selectedSummary.classList.add('d-none');
        beneficiaryInput.value = '';
        selectedBeneficiaryLabel.textContent = '';
        selectedCategoryLabel.textContent = card.dataset.name;
      });
    });

    function renderBeneficiaries(categoryId){
      const category = categories.find(c => c.id === categoryId);
      beneficiaryGrid.innerHTML = '';
      if(!category){ return; }
      beneficiaryStep.classList.remove('d-none');
      beneficiaryGrid.classList.remove('d-none');

      category.beneficiaries.forEach(b => {
        const col = document.createElement('div');
        col.className = 'col-md-6';
        col.innerHTML = `
          <button type="button" class="tile-card w-100 text-start" data-beneficiary-id="${b.id}"
            style="background-image: linear-gradient(180deg, rgba(0,0,0,0.45), rgba(0,0,0,0.55)), url('${b.image ?? '/images/banner-ajuda.png'}');">
            <div class="tile-content">
              <h6 class="mb-1">${b.name}</h6>
              <p class="mb-0 small text-white-50">${(b.description || '').slice(0,100)}</p>
            </div>
          </button>
        `;
        const btn = col.querySelector('button');
        btn.addEventListener('click', () => {
          beneficiaryGrid.querySelectorAll('.tile-card').forEach(c => c.classList.remove('active'));
          btn.classList.add('active');
          beneficiaryInput.value = b.id;
          selectedBeneficiaryLabel.textContent = b.name;
          selectedSummary.classList.remove('d-none');
          donationFormWrapper.classList.remove('d-none');
        });
        beneficiaryGrid.appendChild(col);
      });
    }

    // Valor chips
    const group = document.getElementById('amountGroup');
    const otherGroup = document.getElementById('otherAmountGroup');
    const otherInput = document.getElementById('otherAmount');
    const chips = group.querySelectorAll('.amount-chip input');
    chips.forEach(inp=>{
      inp.addEventListener('change', ()=>{
        group.querySelectorAll('.amount-chip').forEach(c=>c.classList.remove('active'));
        inp.closest('.amount-chip').classList.add('active');
        if (inp.value === 'other') {
          otherGroup.classList.remove('d-none');
          otherGroup.classList.add('d-flex');
          otherGroup.classList.add('compact');
          otherInput.focus();
        } else {
          otherGroup.classList.add('d-none');
          otherInput.value = '';
        }
      });
    });

    // Empresa toggles
    const isCompany = document.getElementById('isCompany');
    const companyNameGroup = document.getElementById('companyNameGroup');
    const nifGroup = document.getElementById('nifGroup');
    const nipcGroup = document.getElementById('nipcGroup');
    isCompany.addEventListener('change', ()=>{
      const on = isCompany.checked;
      companyNameGroup.classList.toggle('d-none', !on);
      nipcGroup.classList.toggle('d-none', !on);
      nifGroup.classList.toggle('d-none', on);
    });

    // Preenchimento de dias/meses/anos (simples)
    const daySel = document.getElementById('birthDay');
    const monSel = document.getElementById('birthMonth');
    const yearSel = document.getElementById('birthYear');
    const meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    for(let d=1; d<=31; d++){ daySel.insertAdjacentHTML('beforeend', `<option value="${d}">${d}</option>`); }
    meses.forEach((m,i)=> monSel.insertAdjacentHTML('beforeend', `<option value="${i+1}">${m}</option>`));
    const yNow = new Date().getFullYear();
    for(let y=yNow; y>=yNow-100; y--){ yearSel.insertAdjacentHTML('beforeend', `<option value="${y}">${y}</option>`); }
  })();
</script>
@endpush
@endsection

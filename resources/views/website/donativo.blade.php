@extends('layouts.website')

@section('body-class', 'donation-bg')

@section('content')
<main class="py-5 donation-bg">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-8">
        <div class="form-shell">
          <h1 class="mb-1" style="color:#157347">Faça um Donativo</h1>
          <p class="text-muted mb-4">
            Escolha o valor que pretende doar e preencha os seus dados. Todos os campos assinalados com * são obrigatórios.
          </p>

          <form id="donationForm" novalidate>
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
                  <option value="">Selecione…</option>
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

      <!-- Sidebar opcional (podes remover) -->
      <div class="col-lg-4">
        <div class="info-card h-100">
          <h5 class="mb-3">Porque o seu gesto conta</h5>
          <ul class="list-unstyled text-muted">
            <li>✔ Apoia directamente famílias vulneráveis</li>
            <li>✔ Transparência e relatório de impacto</li>
            <li>✔ Benefícios fiscais aplicáveis</li>
          </ul>
          <hr>
          <p class="small text-muted mb-0">Tem dúvidas? <a href="/contactos">Fale connosco</a>.</p>
        </div>
      </div>
    </div>
  </div>
</main>

{{-- JS leve para “chips” e condicionais --}}
@push('scripts')
<script>
  (function(){
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


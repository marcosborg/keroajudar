## Integração EuPago Split Payments

### Configuração
- `.env`  
  ```
  EUPAGO_API_KEY=xxxx-xxxx-xxxx-xxxx-xxxx
  EUPAGO_SANDBOX=true
  EUPAGO_CALLBACK_URL=https://seusite.pt/api/eupago/callback
  ```
- `config/eupago.php` controla sandbox, base_url e endpoints.
- Logging dedicado: `storage/logs/eupago.log` (canal `eupago` em `config/logging.php`).

### Fluxo para criar pagamentos
1) MB (Multibanco) split  
   `POST /api/pagamentos/split/multibanco`  
   Corpo:
   ```json
   {
     "valor": 10.0,
     "identificador": "PEDIDO123",
     "beneficiarios": [
       { "externKey": "LOJA_A", "valor_part": 5 },
       { "externKey": "LOJA_B", "valor_part": 5 }
     ]
   }
   ```
   Retorna entidade e referência.

2) MB WAY split  
   `POST /api/pagamentos/split/mbway`  
   Corpo:
   ```json
   {
     "valor": 4.0,
     "identificador": "PEDIDOMBW",
     "alias": "911111111",
     "beneficiarios": [
       { "externKey": "LOJA_A", "valor_part": 2 },
       { "externKey": "LOJA_B", "valor_part": 2 }
     ],
     "descricao": "Opcional"
   }
   ```
   Fica `pendente_callback` até o webhook confirmar.

### Webhook (RealTime 1.0)
- Endpoint: `GET /api/eupago/callback`
- Parâmetros esperados: `identificador`, `valor`, `referencia`, `entidade`, `transacao`, `chave_api`, `mp` etc.
- Validação: `chave_api` deve corresponder a `config('eupago.api_key')`.
- Atualiza pedido para `pago`, regista `callback_payload` e `data_pagamento`. Idempotente: callbacks duplicados são ignorados e logados.
- Preparado para evolução para RT 2.0 (ass. HMAC) — adicionar verificação futura no `EuPagoService::validateWebhook`.

### Estrutura criada
- Serviço: `app/Services/EuPagoService.php`
- Controlador API: `app/Http/Controllers/Api/PagamentoController.php`
- Rotas API: `routes/api.php`
- Config: `config/eupago.php`
- Modelo/Tabela: `pedidos` (`identificador`, `status`, `entidade`, `referencia`, `valor`, `beneficiarios`, `transacao`, `mp`, `data_pagamento`, `callback_payload`, `provider_payload`)
- Log dedicado: canal `eupago`.

### Validações
- `identificador`: alfanumérico/underscore/hífen, máx 64.
- Soma dos `valor_part` deve ser igual a `valor`.
- MB WAY `alias`: 9 dígitos.
- Opcional: `data_limite`, `permite_multiplos` (MB); `descricao` (MB WAY).

### Testes (sandbox/mocks)
- `tests/Feature/EuPagoServiceTest.php` usa `Http::fake` para simular sandbox e verifica criação de pedidos e persistência.
- Para testar com sandbox real, defina `EUPAGO_SANDBOX=true` e `EUPAGO_API_KEY` válidos; envie requisições para os endpoints acima e depois simule pagamento pelo backoffice sandbox, confirmando que o callback muda o `status` para `pago`.

### Como definir beneficiários
- Cada beneficiário requer `externKey` (identificador externo) e `valor_part`.
- Garanta que o somatório de `valor_part` é igual ao `valor` total.

### Como depurar
- Ver `storage/logs/eupago.log` para: falta de API key, respostas inesperadas, callbacks duplicados, erros -7/-10/-12.
- Verificar se `public/storage` está linkado (`php artisan storage:link`) para eventuais uploads de suporte.

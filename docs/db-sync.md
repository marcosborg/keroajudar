# Sincronizar base de dados (produção -> sandbox)

Este projeto tem um comando artisan para copiar a base de dados de produção para a base de dados local/sandbox.

## Pré‑requisitos
- Binários `mysql` e `mysqldump` disponíveis no `PATH`.  
  - Se não estiverem no `PATH`, defina no `.env` os caminhos completos: `MYSQL_BIN="C:/path/para/mysql.exe"` e `MYSQLDUMP_BIN="C:/path/para/mysqldump.exe"`.
- Credenciais no `.env`:
  - Produção (origem): `DB_HOST_PRODUCTION`, `DB_PORT_PRODUCTION`, `DB_DATABASE_PRODUCTION`, `DB_USERNAME_PRODUCTION`, `DB_PASSWORD_PRODUCTION`
  - Sandbox/local (destino): `DB_HOST_SANDBOX`, `DB_PORT_SANDBOX`, `DB_DATABASE_SANDBOX`, `DB_USERNAME_SANDBOX`, `DB_PASSWORD_SANDBOX`

## Comando
```bash
php artisan db:sync-from-production
```

O comando:
1) Mostra as credenciais que vai usar (host/porta/nome da base).  
2) Pede confirmação antes de prosseguir.  
3) Cria um dump da base de dados de produção com `mysqldump`.  
4) Apaga e recria a base de dados local.  
5) Importa o dump para a base de dados local.

### Opções
- `--skip-confirmation` — executa sem pedir confirmação interativa.

## Boas práticas
- Execute sempre num ambiente local/sandbox, nunca em produção.  
- Faça backup local se tiver dados que não queira perder.  
- Após alterar variáveis de ambiente, corra `php artisan config:clear` (ou `php artisan config:cache`) para garantir que a app lê as novas configurações.

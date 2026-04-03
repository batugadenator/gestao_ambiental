# Guia Rápido de Setup (Todos os Passos)

## ✅ Fase 1: Preparação Inicial (30 min)

### 1.1 Copiar arquivo de ambiente
```bash
cp .env.example .env
```

### 1.2 Editar `.env` com suas credenciais
```bash
nano .env  # ou editor de sua preferência
```

**Conteúdo esperado:**
```
DB_HOST=localhost (ou mysql para Docker)
DB_USER=seu_usuario
DB_PASS=sua_senha
DB_NAME=seu_banco
APP_ENV=development (ou production)
```

### 1.3 Atualizar `.gitignore`
✅ Já pronto! Inclui `.env`

---

## ✅ Fase 2: Segurança SQL & CSRF (15 min)

### 2.1 Sistema pronto para uso
- ✅ [config/Database.php](config/Database.php) - Prepared statements
- ✅ [config/csrf.php](config/csrf.php) - Proteção CSRF
- ✅ [config/autoload.php](config/autoload.php) - Carrega tudo

### 2.2 Testar
```bash
# Acessar em navegador
http://localhost/testes_ajax.php

# Ou testar manualmente
curl -X POST http://localhost/cadastros/setores.php \
  -d "carregarDados=sim&csrf_token=seu_token"
```

---

## ✅ Fase 3: Refatoração de Código (20 min)

### 3.1 Login refatorado
File: [login/login.php](login/login.php)
- Usa `Database.php` (prepared statements)
- Inclui CSRF token
- Validações melhoradas

### 3.2 Registrar refatorado
File: [login/registrar.php](login/registrar.php)
- Usa `Database.php`
- Validação de dados
- CSRF protection

### 3.3 Módulos CRUD refatorados
- [cadastros/setores.php](cadastros/setores.php) ✅
- [cadastros/subsecao.php](cadastros/subsecao.php) ✅
- [cadastros/local_ocorrencia.php](cadastros/local_ocorrencia.php) ✅

Todos com:
- Prepared statements
- CSRF tokens
- Validações

---

## ✅ Fase 4: Arquitetura MVC (2-3 horas)

### 4.1 Estrutura criada
```
app/
├── Models/
│   ├── Model.php (Base class com CRUD genérico)
│   ├── Setor.php
│   ├── Subsecao.php
│   └── Usuario.php
├── Controllers/
│   ├── Controller.php (Base class)
│   ├── SetorController.php
│   └── UsuarioController.php
├── Autoloader.php
└── Router.php
```

### 4.2 Como usar
```php
<?php
require 'app/Autoloader.php';

// Usar um controller
$controller = new \App\Controllers\SetorController();
$controller->store();  // Validator, sanitize, response
```

### 4.3 Exemplos prontos
- Model com CRUD: [app/Models/Model.php](app/Models/Model.php)
- Controller exemplo: [app/Controllers/SetorController.php](app/Controllers/SetorController.php)
- Router: [app/Router.php](app/Router.php)

---

## ✅ Fase 5: Docker Setup (15 min)

### 5.1 Arquivos criados
- [Dockerfile](Dockerfile) - PHP 8.2 com extensões
- [docker-compose.yml](docker-compose.yml) - MySQL + PHP + Nginx + phpMyAdmin
- [php.ini](php.ini) - Configurações otimizadas
- [nginx.conf](nginx.conf) - Web server

### 5.2 Uso rápido
```bash
# Build images
docker-compose build

# Startup
docker-compose up -d

# Acessar aplicação
http://localhost

# phpMyAdmin
http://localhost:8080 (root/root_password)
```

### 5.3 Parar
```bash
docker-compose down
```

---

## 🧪 Fase 6: Testes (10 min)

### 6.1 Interface de testes
Abra em navegador: [testes_ajax.php](testes_ajax.php)

Testa:
- ✅ GET/POST/PUT/DELETE endpoints
- ✅ Validações
- ✅ Performance (stress test)
- ✅ CSRF tokens

---

## 📊 Resumo do que foi entregue

| Item | Status | Arquivo |
|------|--------|---------|
| Credenciais em .env | ✅ Pronto | [.env](.env), [.env.example](.env.example) |
| Prepared Statements | ✅ Implementado | [config/Database.php](config/Database.php) |
| CSRF Tokens | ✅ Automático | [config/csrf.php](config/csrf.php) |
| Login refatorado | ✅ Pronto | [login/login.php](login/login.php) |
| Registrar refatorado | ✅ Pronto | [login/registrar.php](login/registrar.php) |
| Testes AJAX | ✅ Interface | [testes_ajax.php](testes_ajax.php) |
| Docker Setup | ✅ Completo | [Dockerfile](Dockerfile), [docker-compose.yml](docker-compose.yml) |
| Arquitetura MVC | ✅ Estrutura | [app/](app/) |
| Documentação | ✅ Completa | [MVC_MIGRATION.md](MVC_MIGRATION.md) |

---

## 🚀 Próximas Etapas Recomendadas

1. **Imediato**
   - [ ] Copiar `.env.example` para `.env`
   - [ ] Configurar credenciais em `.env`
   - [ ] Validar system executando `testes_ajax.php`
   - [ ] Fazer backup do banco de dados

2. **Curto Prazo (1-2 semanas)**
   - [ ] Migrar módulos restantes para MVC
   - [ ] Testes unitários (PHPUnit)
   - [ ] Deploy em Docker

3. **Médio Prazo**
   - [ ] Implementar cache
   - [ ] Logging centralizado
   - [ ] Autenticação melhorada
   - [ ] API REST completa

---

## ❓ FAQ

### P: Preciso alterar código legado?
**R**: Não! Código antigo continua funcionando. MVC é novo padrão para novos recursos.

### P: Como integrar novos módulos com MVC?
**R**: Ver exemplo em [app/Controllers/SetorController.php](app/Controllers/SetorController.php)

### P: Docker é obrigatório?
**R**: Não, continue com PHP/Apache/MySQL local se preferir. Docker é opcional para produção.

### P: Posso usar com PHPMyAdmin?
**R**: Sim! `docker-compose --profile dev up` e acesse `http://localhost:8080`

### P: Como fazer deploy em produção?
**R**: Use Docker! `docker-compose up -d` em seu servidor

---

## 📞 Contato & Suporte

Para dúvidas, ver:
- [SECURITY_REFACTORING.md](SECURITY_REFACTORING.md) - Detalhes sobre segurança
- [MVC_MIGRATION.md](MVC_MIGRATION.md) - Detalhes sobre MVC
- [exemplo_csrf.php](exemplo_csrf.php) - Exemplo de implementação

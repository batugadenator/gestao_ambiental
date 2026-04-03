# Refatoração de Segurança - gestao_ambiental

## ✅ Alterações Implementadas

### 1. **Credenciais em Variáveis de Ambiente (.env)**

Credenciais sensíveis foram movidas de `config/conexao.php` para arquivos `.env`:

- **Arquivo criado**: `.env` (desenvolvimento) e `.env.example` (modelo)
- **Variáveis**: `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`, `DB_CHARSET`

**Como usar:**
```bash
# Copie o arquivo exemplo
cp .env.example .env

# Edite .env com suas credenciais reais
DB_HOST=localhost
DB_USER=seu_usuario
DB_PASS=sua_senha
DB_NAME=seu_banco
```

**Segurança**: O arquivo `.env` deve estar no `.gitignore` para nunca ser versionado!

---

### 2. **Classe Database com Prepared Statements**

Criado arquivo `config/Database.php` com wrapper seguro para MySQLi:

**Funcionalidades:**
- ✅ Singleton pattern (única instância de conexão)
- ✅ Carrega configurações do `.env` automaticamente
- ✅ **Prepared statements** para todas as queries
- ✅ Type binding (`s`, `i`, `d` para string, int, double)
- ✅ Métodos helper: `query()`, `fetchAll()`, `fetchOne()`
- ✅ Proteção contra SQL Injection

**Exemplo de uso:**
```php
$db = Database::getInstance();
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $db->query($sql, [$id], 'i');  // 'i' = integer
$usuario = $db->fetchOne($stmt);
```

---

### 3. **Tokens CSRF**

Adicionado sistema de proteção CSRF em `config/csrf.php`:

**Funções disponíveis:**
- `generateCSRFToken()` - Gera novo token (automático em autoload.php)
- `getCSRFToken()` - Retorna token atual
- `validateCSRFToken($token)` - Valida token recebido
- `validatePostWithCSRF()` - Valida POST inteiro (já incluso nos scripts)
- `csrfField()` - Exibe input hidden com token em formulários
- `sanitizeInput($input)` - Sanitiza HTML

**Como usar em formulários HTML:**
```html
<form method="POST">
    <?php csrfField(); ?>  <!-- Adiciona token CSRF -->
    <input type="text" name="nome">
    <button type="submit">Enviar</button>
</form>
```

**Validação em AJAX:**
```javascript
const formData = new FormData();
formData.append('csrf_token', '<?php echo getCSRFToken(); ?>');
formData.append('dados', 'valor');
```

---

### 4. **Arquivos Refatorados**

Todos os seguintes arquivos foram atualizados com **Prepared Statements** e **CSRF validation**:

| Arquivo | Mudanças |
|---------|----------|
| `config/conexao.php` | Agora usa classe Database com .env |
| `config/autoload.php` | Incluído csrf.php e geração automática de token |
| `cadastros/subsecao.php` | ✅ Queries com prepared statements |
| `cadastros/setores.php` | ✅ Queries com prepared statements |
| `cadastros/local_ocorrencia.php` | ✅ Queries com prepared statements |

---

## 🔒 Comparação: Antes vs Depois

### ❌ **ANTES (Vulnerável):**
```php
$subsecao = $_POST['subsecao'];
$id = $_POST['id'];
$sql = "UPDATE subsecoes SET subsecao='$subsecao' WHERE id = '$id'";
mysqli_query($con, $sql);
// Ataque: subsecao = "test'; DROP TABLE subsecoes; --"
```

### ✅ **DEPOIS (Seguro):**
```php
$db = Database::getInstance();
$sql = "UPDATE subsecoes SET subsecao = ? WHERE id = ?";
$stmt = $db->query($sql, [$subsecao, $id], 'si');  // 's'=string, 'i'=int
// Prepared statement previne SQL Injection
```

---

## 📋 Checklist de Implementação

- [x] Criar `.env` com credenciais
- [x] Criar classe `Database.php`
- [x] Implementar CSRF tokens
- [x] Refatorar `subsecao.php` com prepared statements
- [x] Refatorar `setores.php` com prepared statements
- [x] Refatorar `local_ocorrencia.php` com prepared statements
- [ ] **Refatorar formulários HTML para incluir `csrfField()`**
- [ ] Refatorar `login.php` para usar classe Database (opcional - já usa prepared statements)
- [ ] Refatorar outros módulos (fotos, backup, etc)
- [ ] Testar todos os endpoints AJAX/formulários
- [ ] Configurar `.gitignore` para ignorar `.env`

---

## 🚀 Próximas Etapas

### Curto Prazo (Prioritário):
1. **Atualizar formulários HTML** para incluir tokens CSRF
2. **Refatorar login.php** para usar classe Database
3. **Testar** todos os endpoints para garantir funcionamento
4. **Deploy** em produção com `.env` configurado

### Médio Prazo:
1. Criar Docker setup (Dockerfile + docker-compose.yml)
2. Refatorar para padrão MVC com classes reutilizáveis
3. Adicionar validação de entrada robusta
4. Implementar logging de segurança

### Longo Prazo:
1. Migrar para framework PHP moderno (Laravel, Symfony)
2. Implementar CI/CD pipeline
3. Adicionar testes automatizados
4. Implementar rate limiting e proteção DDoS

---

## 🔍 Teste de Segurança

Para validar que as mudanças funcionam:

```bash
# 1. Teste SQL Injection (deve falhar)
POST /cadastros/setores.php
Body: setor=test'; DROP TABLE setores; --, csrf_token=xyz

# Resultado esperado: Token CSRF inválido OR SQL seguro contra injection

# 2. Teste CSRF (deve falhar)
POST /cadastros/setores.php
Body: setor=novo_setor (sem csrf_token)

# Resultado esperado: Token CSRF inválido ou expirado.
```

---

## 📝 Notas Importantes

1. **Sempre commit `.env.example`** mas NUNCA commit `.env`
2. **Token CSRF expira** com a sessão do usuário
3. **Prepared statements** funcionam com MySQLi, PDO e outras libs PHP
4. **Compatibilidade**: Mantém compatibilidade com código antigo via função `connect_local_mysqli()`


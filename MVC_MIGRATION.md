# Refatoração Completa - gestao_ambiental

## ✅ Etapas Completadas

### 1. **Credenciais em Variáveis de Ambiente** ✅
- [.env](.env) e [.env.example](.env.example) criados
- Classe `Database.php` carrega automaticamente
- Arquivo `.env` adicionado ao `.gitignore`

### 2. **Prepared Statements** ✅
- Classe `Database.php` com wrapper seguro
- Todos os 3 módulos refatorados (setores, subsecoes, local_ocorrencia)
- **SQL Injection eliminada**

### 3. **Tokens CSRF** ✅
- Sistema completo em `config/csrf.php`
- Integrado automaticamente em `config/autoload.php`
- Validação em todos os POST/PUT/DELETE
- Exemplo em [exemplo_csrf.php](exemplo_csrf.php)

### 4. **Formulários HTML com CSRF** ✅
- [login/login.php](login/login.php) - Atualizado com `<?php csrfField(); ?>`
- [login/registrar.php](login/registrar.php) - Refatorado com Database.php
- [fotos/visualizar_fotos.php](fotos/visualizar_fotos.php) - Pode ser atualizado

### 5. **Refatoração de Login** ✅
- [login/login.php](login/login.php) agora usa `Database.php`
- [login/registrar.php](login/registrar.php) que usa `Database.php`
- Validação melhorada
- CSRF token implementado

### 6. **Testes AJAX** ✅
- [testes_ajax.php](testes_ajax.php) criado
- Testes para todos os endpoints
- Stress tests inclusos
- Interface amigável tipo Postman

### 7. **Docker Setup** ✅
- [Dockerfile](Dockerfile) - PHP 8.2-FPM com todas as extensões
- [docker-compose.yml](docker-compose.yml) - MySQL, PHP, Nginx, phpMyAdmin
- [php.ini](php.ini) - Configurações otimizadas
- [nginx.conf](nginx.conf) - Server Web configurado
- `.env` automático para Docker

### 8. **Arquitetura MVC** ✅
- **Models**: `app/Models/Model.php` (com CRUD genérico)
  - `Setor.php`, `Subsecao.php`, `Usuario.php`
  - Validação integrada
  - Queries preparadas
  
- **Controllers**: `app/Controllers/Controller.php` (base)
  - `SetorController.php`
  - `UsuarioController.php`
  - Validação, sanitização, resposta JSON
  
- **Autoloader**: `app/Autoloader.php`
- **Router**: `app/Router.php` (roteamento simples)

---

## 🚀 Como Usar

### Estrutura de Diretórios
```
gestao_ambiental-main/
├── .env                      # Configurações (não versionar)
├── .env.example              # Template de exemplo
├── .gitignore                # Git ignore (contém .env)
├── Dockerfile                # Build Docker
├── docker-compose.yml        # Orquestração Docker
├── php.ini                   # Configurações PHP
├── nginx.conf                # Configuração Nginx
│
├── app/                       # Nova estrutura MVC
│   ├── Autoloader.php        # PSR-4 autoloader
│   ├── Router.php            # Roteador simples
│   ├── Models/               # Models de dados
│   │   ├── Model.php         # Base class com CRUD
│   │   ├── Setor.php
│   │   ├── Subsecao.php
│   │   └── Usuario.php
│   ├── Controllers/          # Controllers
│   │   ├── Controller.php    # Base class
│   │   ├── SetorController.php
│   │   └── UsuarioController.php
│   └── Views/               # Templates (vazio - prepare views aqui)
│
├── config/                    # Configurações
│   ├── autoload.php          # Bootstrap
│   ├── conexao.php           # Usa Database.php
│   ├── Database.php          # ⭐ Classe principal
│   ├── csrf.php              # Proteção CSRF
│   ├── global_constraints.php
│   └── includes.php
│
├── cadastros/                # Módulos antigos
│   ├── setores.php           # ✅ Refatorado (prepared statements)
│   ├── subsecao.php          # ✅ Refatorado
│   └── local_ocorrencia.php  # ✅ Refatorado
│
├── login/                     # Autenticação
│   ├── login.php             # ✅ Refatorado com Database.php + CSRF
│   └── registrar.php         # ✅ Refatorado com Database.php + CSRF
│
├── testes_ajax.php           # 🧪 Interface de testes
├── exemplo_csrf.php          # 📚 Exemplo de implementação
└── SECURITY_REFACTORING.md   # Documentação anterior
```

---

## 🐳 Docker Setup

### Inicializar

```bash
# 1. Clonar/preparar projeto
cd gestao_ambiental-main

# 2. Copiar arquivo de ambiente
cp .env.example .env

# 3. Editar .env se necessário
nano .env

# 4. Construir imagens
docker-compose build

# 5. Iniciar containers
docker-compose up -d

# 6. Aplicação roda em
# http://localhost
# phpMyAdmin em http://localhost:8080
```

### Parar

```bash
docker-compose down
```

### Logs

```bash
docker-compose logs -f php
docker-compose logs -f mysql
docker-compose logs -f nginx
```

### Executar comandos dentro do container

```bash
docker-compose exec php bash
mysql -h mysql -u root -p  # Dentro do container
```

---

## 🏗️ Padrão MVC - Como Usar

### Exemplo: Criar novo Model

```php
<?php
namespace App\Models;

class Produto extends Model
{
    protected $table = 'produtos';

    public function validate($data)
    {
        $errors = [];
        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome é obrigatório';
        }
        return $errors;
    }
}
```

### Exemplo: Criar novo Controller

```php
<?php
namespace App\Controllers;
use App\Models\Produto;

class ProdutoController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Produto();
    }

    public function store()
    {
        $data = $this->getAllInput();
        
        // Validar
        $errors = $this->model->validate($data);
        if (!empty($errors)) {
            return $this->json(['errors' => $errors], 422);
        }

        // Sanitizar
        $data = $this->sanitize($data);

        // Criar
        $id = $this->model->create($data);

        if (!$id) {
            return $this->json($this->error('Erro ao criar'), 500);
        }

        return $this->json($this->success('Criado', $this->model->findById($id)), 201);
    }
}
```

### Usar novoController

```php
<?php
require_once 'app/Autoloader.php';

$router = new Router();
$router->post('/api/produtos', 'ProdutoController@store');
$router->get('/api/produtos', 'ProdutoController@index');
$router->run();
```

---

## 🧪 Testes AJAX

Acesse [testes_ajax.php](testes_ajax.php) para:
- ✅ Testar todos os endpoints
- ✅ Testes de carga (5, 20, 50 requisições simultâneas)
- Performance monitoring
- Interface visual amigável

---

## 🔒 Segurança Implementada

| Aspecto | Antes | Depois |
|--------|-------|--------|
| **Credenciais** | Hardcoded em .php | Variáveis de ambiente (.env) |
| **SQL Injection** | Queries diretas | Prepared Statements |
| **CSRF** | Sem proteção | Tokens automáticos |
| **Validação** | Parcial | Robusta em Models |
| **Hashing** | ✓ Já tinha | ✓ Mantido |

---

## 📊 Comparação: Antes vs Depois

### Antes (Vulnerável)
```php
$subsecao = $_POST['subsecao'];
$id = $_POST['id'];
$sql = "UPDATE subsecoes SET subsecao='$subsecao' WHERE id = '$id'";
mysqli_query($con, $sql);
```

### Depois (Seguro)
```php
// Option A: Usar class nova
$controller = new SetorController();
$controller->update($id);  // Valida, sanitiza, exec

// Option B: Usar Model direto
$model = new Setor();
$model->update($id, ['setor' => $subsecao]);

// Option C: SQL com prepared statement
$db = Database::getInstance();
$stmt = $db->query("UPDATE setores SET setor = ? WHERE id = ?", [$subsecao, $id], 'si');
```

---

## 📝 Nomear Convenções

- **Models**: Singular, PascalCase (`Setor`, `Usuario`)
- **Controllers**: Singular + "Controller", PascalCase (`SetorController`)
- **Tables**: Plural, snake_case (`setores`, `usuarios`)
- **Methods**: camelCase (`getById`, `findByName`)
- **Constants**: UPPER_SNAKE_CASE (`DB_HOST`)

---

## 🎯 Próximos Passos (Futuro)

1. **Migrar todos os módulos para MVC**
   - fotos → FotoController
   - backup → BackupController
   - registros → RegistroController

2. **Melhorias**
   - Cache de banco de dados
   - Logging centralizado
   - Middleware de autenticação
   - Validação em servidor e cliente

3. **Documentação API**
   - OpenAPI/Swagger
   - Documentação de endpoints
   - Exemplos de uso

4. **Testes**
   - Testes unitários (PHPUnit)
   - Testes de integração
   - CI/CD (GitHub Actions, GitLab CI)

---

## 🆘 Troubleshooting

### "Token CSRF inválido"
- Verifique se `config/csrf.php` está incluído em `config/autoload.php`
- Certifique-se de usar `<?php csrfField(); ?>` em formulários
- Ou inclua `csrf_token` em requisições AJAX: `formData.append('csrf_token', '<?php echo getCSRFToken(); ?>')`

### "Database connection failed"
- Verifique `.env` com credenciais corretas
- Reinicie o container MySQL: `docker-compose restart mysql`
- Verifique logs: `docker-compose logs mysql`

### "Class not found"
- Certifique-se de que `app/Autoloader.php` foi incluído
- Verifique namespace: deve ser `App\Models\` ou `App\Controllers\`
- Arquivo deve estar em local correto: `app/Models/MeuModel.php`

---

## 📞 Suporte

Para dúvidas sobre:
- **Database.php**: Ver documentação em [config/Database.php](config/Database.php)
- **CSRF**: Ver exemplo em [exemplo_csrf.php](exemplo_csrf.php)
- **MVC**: Ver padrão em [app/Controllers/SetorController.php](app/Controllers/SetorController.php)
- **Docker**: Ver [docker-compose.yml](docker-compose.yml)


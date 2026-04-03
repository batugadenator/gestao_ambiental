<?php
session_start();
session_regenerate_id(true);

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/csrf.php';

/**
 * Processa login de usuário com Database.php
 * @param string $login Nome de usuário
 * @param string $senha Senha em claro
 * @return array ['success' => bool, 'message' => string]
 */
function login($login, $senha)
{
    $db = Database::getInstance();
    
    // Validação básica
    if (empty(trim($login)) || empty(trim($senha))) {
        return ['success' => false, 'message' => 'Login e senha são obrigatórios.'];
    }

    // Login com prepared statement
    $sql = "SELECT id, usuario, email, senha, admin FROM usuarios WHERE usuario = ?";
    $stmt = $db->query($sql, [$login], 's');

    if (!$stmt) {
        return ['success' => false, 'message' => 'Erro ao processar login.'];
    }

    $usuario = $db->fetchOne($stmt);

    if (!$usuario || !password_verify($senha, $usuario['senha'])) {
        return ['success' => false, 'message' => 'Login ou senha incorreta.'];
    }

    // Login bem-sucedido
    $_SESSION['id'] = $usuario['id'];
    $_SESSION['usuario'] = $usuario['usuario'];
    $_SESSION['email'] = $usuario['email'];
    $_SESSION['admin'] = $usuario['admin'];
    $_SESSION['nome'] = mb_convert_case($usuario['usuario'], MB_CASE_TITLE);
    $_SESSION['timeout'] = strtotime('+2 hours');

    // Cookie seguro
    setcookie(
        "sess",
        base64_encode(serialize($_SESSION)),
        0,
        '/',
        $_ENV['APP_ENV'] === 'production' ? ".gestambi.com.br" : "",
        $_ENV['APP_ENV'] === 'production',  // https only em produção
        true  // http only
    );

    return ['success' => true, 'message' => 'Login realizado com sucesso.', 'redirect' => HOME_URL . '/fotos/visualizar_fotos.php'];
}

$notfy = "";
$result = null;

// Processar login apenas se for POST e CSRF válido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validatePostWithCSRF();  // Valida CSRF ou morre
    
    $login = sanitizeInput(mb_convert_case($_POST['login'] ?? "", MB_CASE_UPPER));
    $senha = $_POST['senha'] ?? "";
    
    $result = login($login, $senha);
    
    if ($result['success']) {
        // Redirecionar após login bem-sucedido
        header("Location: " . $result['redirect']);
        exit;
    } else {
        $notfy = "var notyf = new Notyf({delay: 5000}); notyf.alert('" . addslashes($result['message']) . "');";
    }
}
?>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,height=device-height, initial-scale=1">
    <meta http-equiv="Content-Language" content="pt-br">
    <meta name="description" content="Sistema de Gestão Ambiental">
    <meta name="author" content="Sistema de Gestão Ambiental - Desenvolvido por Douglas Marcondes">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Sistema de Gestão Ambiental</title>
    <link rel="icon" href="/includes/logo.ico">
</head>

<style>
    * {
        box-sizing: border-box;
        outline: none;
    }

    .form-control {
        font-size: 16px;
        padding: 10px;
    }

    body {
        background-size: cover;
    }

    .login-form {
        margin-top: 60px;
    }

    form[role=login] {
        color: #5d5d5d;
        background: #f2f2f2;
        padding: 20px;
        border-radius: 10px;
    }

    form[role=login] img {
        display: block;
        margin: 0 auto;
    }

    form[role=login] input,
    form[role=login] button {
        font-size: 18px;
        margin: 16px 0;
    }

    form[role=login]>div {
        text-align: center;
    }

    .form-links {
        text-align: center;
        margin-top: 1em;
    }

    .form-links a {
        color: #5d5d5d;
        text-decoration: underline;
    }

    .login-button {
        display: block;
        margin: 0 auto;
        width: 100%;
    }
</style>

<body>
    <div class="container">
        <div class="row" id="pwd-container">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <section class="login-form">
                    <form method="post" action="" role="login">
                        <!-- ✅ Token CSRF para segurança -->
                        <?php csrfField(); ?>
                        
                        <img class="logo" src="../includes/logo.ico" alt="Logo" style="max-height: 200px;">
                        <div id="title" style="font-family: 'Philosopher', sans-serif; font-size: 2.5em; text-align: center;">Sistema de Gestão Ambiental</div>
                        <input type="text" class="form-control input-lg" name="login" id="login" placeholder="Login" required="required" />
                        <input type="password" class="form-control input-lg" name="senha" id="senha" placeholder="Senha" required="required" />
                        <button type="submit" name="login_submit" class="btn btn-lg btn-success login-button">Login</button>
                        <div>
                            <a href="<?php echo HOME_URL; ?>login/recuperar_senha.php">Esqueci Minha Senha</a>
                        </div>
                    </form>
                    <div class="form-links">
                        <a href="<?php echo HOME_URL; ?>login/registrar.php">Não possui uma conta? Cadastre-se</a>
                    </div>
                </section>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>

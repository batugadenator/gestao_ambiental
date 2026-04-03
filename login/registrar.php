<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/conexao.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/csrf.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validar CSRF
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Token CSRF inválido."]);
        exit;
    }

    $db = Database::getInstance();
    
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $admin = $_POST['admin'] ?? '0';

    // Validação
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Preencha todos os campos obrigatórios."]);
        exit;
    }

    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Email inválido."]);
        exit;
    }

    // Validar comprimento de senha
    if (strlen($password) < 6) {
        echo json_encode(["status" => "error", "message" => "Senha deve ter pelo menos 6 caracteres."]);
        exit;
    }

    // Verificar duplicatas
    $sql = "SELECT id FROM usuarios WHERE usuario = ? OR email = ?";
    $stmt = $db->query($sql, [$username, $email], 'ss');

    if ($stmt) {
        $existente = $db->fetchOne($stmt);
        if ($existente) {
            echo json_encode(["status" => "error", "message" => "Nome de usuário ou email já cadastrado."]);
            exit;
        }
    }

    // Hash da senha e cadastro
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $admin_flag = ($admin === '1') ? '1' : '0';

    $sql = "INSERT INTO usuarios (nome, usuario, senha, email, admin) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->query($sql, [$username, $username, $hashed_password, $email, $admin_flag], 'sssss');

    if ($stmt && $db->affectedRows() > 0) {
        // Opcional: fazer login automático após registro
        $_SESSION['usuario'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['admin'] = $admin_flag;
        $_SESSION['nome'] = mb_convert_case($username, MB_CASE_TITLE);
        
        echo json_encode(["status" => "success", "message" => "Usuário cadastrado com sucesso.", "redirect" => HOME_URL . "/fotos/visualizar_fotos.php"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao cadastrar usuário. Tente novamente."]);
    }
    exit;
}
?>
<?php

/**
 * CSRF Token Helper
 * Funções para gerar, validar e incluir tokens CSRF
 */

/**
 * Gera um novo token CSRF
 */
function generateCSRFToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Retorna o token CSRF da sessão
 */
function getCSRFToken()
{
    return $_SESSION['csrf_token'] ?? '';
}

/**
 * Validar se o token CSRF enviado é válido
 * @param string $token Token do formulário
 * @return bool True se válido
 */
function validateCSRFToken($token)
{
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Função wrapper para validar POST com CSRF
 * Deve ser chamada no início de scripts que recebem POST
 */
function validatePostWithCSRF()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return true;
    }

    $token = $_POST['csrf_token'] ?? '';
    
    if (!validateCSRFToken($token)) {
        header('Content-Type: application/json');
        http_response_code(403);
        echo json_encode(["error" => "Token CSRF inválido ou expirado."]);
        exit;
    }

    return true;
}

/**
 * Exibe campo oculto com CSRF token (para HTML forms)
 */
function csrfField()
{
    $token = generateCSRFToken();
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Sanitiza input (HTML encode)
 */
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

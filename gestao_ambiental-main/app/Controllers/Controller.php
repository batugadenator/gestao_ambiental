<?php

namespace App\Controllers;

use config\Database;

/**
 * Classe base para todos os controllers
 * Fornece métodos comuns para tratamento de requisições
 */
abstract class Controller
{
    protected $db;
    protected $model;
    protected $response = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Retorna resposta JSON padronizada
     */
    protected function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Resposta de sucesso
     */
    protected function success($message = null, $data = null, $redirect = null)
    {
        $response = [
            'status' => 'success',
            'message' => $message ?? 'Operação realizada com sucesso.'
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($redirect !== null) {
            $response['redirect'] = $redirect;
        }

        return $response;
    }

    /**
     * Resposta de erro
     */
    protected function error($message, $code = 400)
    {
        $response = [
            'status' => 'error',
            'message' => $message ?? 'Ocorreu um erro.'
        ];

        return $this->json($response, $code);
    }

    /**
     * Valida entrada do usuário
     */
    protected function validate($data, $rules)
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;

            // required
            if (strpos($rule, 'required') !== false && empty($value)) {
                $errors[$field] = "Campo obrigatório.";
                continue;
            }

            // min:X
            if (preg_match('/min:(\d+)/', $rule, $match)) {
                if (strlen($value) < $match[1]) {
                    $errors[$field] = "Mínimo {$match[1]} caracteres.";
                }
            }

            // max:X
            if (preg_match('/max:(\d+)/', $rule, $match)) {
                if (strlen($value) > $match[1]) {
                    $errors[$field] = "Máximo {$match[1]} caracteres.";
                }
            }

            // email
            if (strpos($rule, 'email') !== false && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "Email inválido.";
            }

            // numeric
            if (strpos($rule, 'numeric') !== false && !is_numeric($value)) {
                $errors[$field] = "Deve ser numérico.";
            }
        }

        return empty($errors) ? [] : $errors;
    }

    /**
     * Sanitiza entrada
     */
    protected function sanitize($data)
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitize($value);
            } else {
                $sanitized[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
            }
        }
        return $sanitized;
    }

    /**
     * Retorna parâmetro GET/POST
     */
    protected function getInput($key, $default = null)
    {
        return $_REQUEST[$key] ?? $default;
    }

    /**
     * Retorna todos os inputs
     */
    protected function getAllInput()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST;
        }
        return $_GET;
    }

    /**
     * Renderiza view
     */
    protected function render($view, $data = [])
    {
        extract($data);
        include __DIR__ . "/../Views/{$view}.php";
    }
}

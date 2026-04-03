<?php

namespace App\Controllers;

use App\Models\Usuario;

/**
 * Controller para autenticação e gestão de usuários
 */
class UsuarioController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Usuario();
    }

    /**
     * Fazer login
     */
    public function login()
    {
        try {
            $data = $this->getAllInput();

            if (empty($data['usuario']) || empty($data['senha'])) {
                return $this->json($this->error('Usuário e senha são obrigatórios'), 422);
            }

            // Autenticar
            $usuario = $this->model->authenticate($data['usuario'], $data['senha']);

            if (!$usuario) {
                return $this->json($this->error('Usuário ou senha inválidos'), 401);
            }

            // Criar sessão
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['usuario'] = $usuario['usuario'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['admin'] = $usuario['admin'];
            $_SESSION['nome'] = mb_convert_case($usuario['usuario'], MB_CASE_TITLE);

            return $this->json($this->success('Login realizado com sucesso', [
                'usuario' => $usuario['usuario'],
                'email' => $usuario['email']
            ]));
        } catch (\Exception $e) {
            return $this->json($this->error($e->getMessage()), 500);
        }
    }

    /**
     * Registrar novo usuário
     */
    public function register()
    {
        try {
            $data = $this->getAllInput();

            // Validar
            $errors = $this->model->validate($data, true);
            if (!empty($errors)) {
                return $this->json(['status' => 'error', 'errors' => $errors], 422);
            }

            // Verificar duplicatas
            if ($this->model->findByUsername($data['usuario'])) {
                return $this->json($this->error('Nome de usuário já existe'), 409);
            }

            if ($this->model->findByEmail($data['email'])) {
                return $this->json($this->error('Email já cadastrado'), 409);
            }

            // Sanitizar
            $data = $this->sanitize($data);

            // Criar usuário
            $id = $this->model->create([
                'nome' => $data['usuario'],
                'usuario' => $data['usuario'],
                'email' => $data['email'],
                'senha' => $data['senha'],
                'admin' => $data['admin'] ?? '0'
            ]);

            if (!$id) {
                return $this->json($this->error('Erro ao criar usuário'), 500);
            }

            return $this->json($this->success('Usuário cadastrado com sucesso', ['id' => $id]), 201);
        } catch (\Exception $e) {
            return $this->json($this->error($e->getMessage()), 500);
        }
    }

    /**
     * Fazer logout
     */
    public function logout()
    {
        session_destroy();
        return $this->json($this->success('Logout realizado com sucesso'));
    }

    /**
     * Obter usuário atual
     */
    public function me()
    {
        if (empty($_SESSION['id'])) {
            return $this->json($this->error('Não autenticado'), 401);
        }

        try {
            $usuario = $this->model->findById($_SESSION['id']);
            if (!$usuario) {
                return $this->json($this->error('Usuário não encontrado'), 404);
            }

            unset($usuario['senha']);  // Não retornar senha
            return $this->json($this->success('Usuário carregado', $usuario));
        } catch (\Exception $e) {
            return $this->json($this->error($e->getMessage()), 500);
        }
    }
}

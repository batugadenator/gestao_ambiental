<?php

namespace App\Models;

/**
 * Model para tabela 'usuarios'
 */
class Usuario extends Model
{
    protected $table = 'usuarios';

    /**
     * Busca usuário por nome de usuário
     */
    public function findByUsername($username)
    {
        $result = $this->findWhere(['usuario' => $username]);
        return $result[0] ?? null;
    }

    /**
     * Busca usuário por email
     */
    public function findByEmail($email)
    {
        $result = $this->findWhere(['email' => $email]);
        return $result[0] ?? null;
    }

    /**
     * Valida dados de usuário
     */
    public function validate($data, $isNew = true)
    {
        $errors = [];

        if (empty(trim($data['usuario'] ?? ''))) {
            $errors['usuario'] = 'Nome de usuário é obrigatório.';
        } elseif (strlen($data['usuario']) < 3) {
            $errors['usuario'] = 'Nome de usuário deve ter pelo menos 3 caracteres.';
        }

        if (empty($data['email'] ?? '')) {
            $errors['email'] = 'Email é obrigatório.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido.';
        }

        if (!empty($data['senha'] ?? '')) {
            if (strlen($data['senha']) < 6) {
                $errors['senha'] = 'Senha deve ter pelo menos 6 caracteres.';
            }
        } elseif ($isNew) {
            $errors['senha'] = 'Senha é obrigatória.';
        }

        return $errors;
    }

    /**
     * Cria usuário com senha hasheada
     */
    public function create($data)
    {
        if (isset($data['senha'])) {
            $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }
        return parent::create($data);
    }

    /**
     * Atualiza usuário com senha hasheada (se fornecida)
     */
    public function update($id, $data)
    {
        if (isset($data['senha']) && !empty($data['senha'])) {
            $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        } else {
            unset($data['senha']);  // Não atualizar senha se não for fornecida
        }
        return parent::update($id, $data);
    }

    /**
     * Autentica usuário
     */
    public function authenticate($username, $password)
    {
        $usuario = $this->findByUsername($username);

        if (!$usuario || !password_verify($password, $usuario['senha'])) {
            return null;
        }

        return $usuario;
    }
}

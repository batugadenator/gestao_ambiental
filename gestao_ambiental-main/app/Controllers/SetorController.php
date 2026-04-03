<?php

namespace App\Controllers;

use App\Models\Setor;

/**
 * Controller para gerenciar Setores
 * 
 * Exemplo de uso:
 *   POST /api/setores/store  -> criar novo
 *   POST /api/setores/index  -> listar todos
 *   POST /api/setores/show   -> obter por ID
 *   POST /api/setores/update -> atualizar
 *   POST /api/setores/delete -> deletar
 */
class SetorController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Setor();
    }

    /**
     * Listar todos os setores
     */
    public function index()
    {
        try {
            $setores = $this->model->getAllWithSubsections();
            return $this->json($this->success('Setores carregados', $setores));
        } catch (\Exception $e) {
            return $this->json($this->error($e->getMessage()), 500);
        }
    }

    /**
     * Obter setor por ID
     */
    public function show($id)
    {
        try {
            $setor = $this->model->findById($id);

            if (!$setor) {
                return $this->json($this->error('Setor não encontrado'), 404);
            }

            return $this->json($this->success('Setor encontrado', $setor));
        } catch (\Exception $e) {
            return $this->json($this->error($e->getMessage()), 500);
        }
    }

    /**
     * Criar novo setor
     */
    public function store()
    {
        try {
            $data = $this->getAllInput();

            // Validar
            $errors = $this->model->validate($data);
            if (!empty($errors)) {
                return $this->json(['status' => 'error', 'errors' => $errors], 422);
            }

            // Sanitizar
            $data = $this->sanitize(['setor' => $data['setor']]);

            // Criar
            $id = $this->model->create(['setor' => $data['setor']]);

            if (!$id) {
                return $this->json($this->error('Erro ao criar setor'), 500);
            }

            $setor = $this->model->findById($id);
            return $this->json($this->success('Setor criado com sucesso', $setor), 201);
        } catch (\Exception $e) {
            return $this->json($this->error($e->getMessage()), 500);
        }
    }

    /**
     * Atualizar setor
     */
    public function update($id)
    {
        try {
            $setor = $this->model->findById($id);

            if (!$setor) {
                return $this->json($this->error('Setor não encontrado'), 404);
            }

            $data = $this->getAllInput();

            // Validar
            $errors = $this->model->validate($data);
            if (!empty($errors)) {
                return $this->json(['status' => 'error', 'errors' => $errors], 422);
            }

            // Sanitizar
            $data = $this->sanitize(['setor' => $data['setor']]);

            // Atualizar
            $updated = $this->model->update($id, ['setor' => $data['setor']]);

            if (!$updated) {
                return $this->json($this->error('Erro ao atualizar setor'), 500);
            }

            $setor = $this->model->findById($id);
            return $this->json($this->success('Setor atualizado com sucesso', $setor));
        } catch (\Exception $e) {
            return $this->json($this->error($e->getMessage()), 500);
        }
    }

    /**
     * Deletar setor
     */
    public function delete($id)
    {
        try {
            $setor = $this->model->findById($id);

            if (!$setor) {
                return $this->json($this->error('Setor não encontrado'), 404);
            }

            $deleted = $this->model->delete($id);

            if (!$deleted) {
                return $this->json($this->error('Erro ao deletar setor'), 500);
            }

            return $this->json($this->success('Setor deletado com sucesso'));
        } catch (\Exception $e) {
            return $this->json($this->error($e->getMessage()), 500);
        }
    }
}

<?php

namespace App\Models;

/**
 * Model para tabela 'setores'
 */
class Setor extends Model
{
    protected $table = 'setores';

    /**
     * Obtém todos os setores com suas subsecções
     */
    public function getAllWithSubsections()
    {
        $sql = "
            SELECT s.*, COUNT(sub.id) as total_subsecoes
            FROM {$this->table} s
            LEFT JOIN subsecoes sub ON sub.setor_superior = s.id
            GROUP BY s.id
            ORDER BY s.setor ASC
        ";
        $stmt = $this->db->query($sql);
        return $this->db->fetchAll($stmt);
    }

    /**
     * Busca por nome
     */
    public function findByName($name)
    {
        return $this->findWhere(['setor' => $name]);
    }

    /**
     * Valida dados de setor
     */
    public function validate($data)
    {
        $errors = [];

        if (empty(trim($data['setor'] ?? ''))) {
            $errors['setor'] = 'Nome do setor é obrigatório.';
        }

        if (strlen($data['setor'] ?? '') > 255) {
            $errors['setor'] = 'Nome não pode exceder 255 caracteres.';
        }

        return $errors;
    }
}

<?php

namespace App\Models;

/**
 * Model para tabela 'subsecoes'
 */
class Subsecao extends Model
{
    protected $table = 'subsecoes';

    /**
     * Obtém todas as subsecções com informações do setor
     */
    public function getAllWithSetores()
    {
        $sql = "
            SELECT sub.*, s.setor as setor_nome
            FROM {$this->table} sub
            LEFT JOIN setores s ON s.id = sub.setor_superior
            ORDER BY s.setor ASC, sub.subsecao ASC
        ";
        $stmt = $this->db->query($sql);
        return $this->db->fetchAll($stmt);
    }

    /**
     * Obtém subsecções por setor superior
     */
    public function findBySetorSuperior($setor_id)
    {
        return $this->findWhere(['setor_superior' => $setor_id]);
    }

    /**
     * Valida dados de subsecção
     */
    public function validate($data)
    {
        $errors = [];

        if (empty(trim($data['subsecao'] ?? ''))) {
            $errors['subsecao'] = 'Nome da subsecção é obrigatório.';
        }

        if (empty($data['setor_superior'] ?? null)) {
            $errors['setor_superior'] = 'Setor superior é obrigatório.';
        } elseif (!is_numeric($data['setor_superior'])) {
            $errors['setor_superior'] = 'Setor superior deve ser numérico.';
        }

        return $errors;
    }
}

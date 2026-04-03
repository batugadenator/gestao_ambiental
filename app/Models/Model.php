<?php

namespace App\Models;

use config\Database;

/**
 * Classe base para todos os models
 * Fornece métodos comuns para CRUD
 */
abstract class Model
{
    protected $table;
    protected $db;
    protected $id;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Encontra um registro por ID
     */
    public function findById($id)
    {
        if (!$this->table) {
            throw new \Exception("Tabela não definida no model");
        }

        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->query($sql, [$id], 'i');
        return $this->db->fetchOne($stmt);
    }

    /**
     * Busca todos os registros
     */
    public function findAll()
    {
        if (!$this->table) {
            throw new \Exception("Tabela não definida no model");
        }

        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $this->db->fetchAll($stmt);
    }

    /**
     * Busca registros com condições
     * @param array $where ['coluna' => 'valor', ...]
     */
    public function findWhere($where)
    {
        if (!$this->table) {
            throw new \Exception("Tabela não definida no model");
        }

        $conditions = [];
        $params = [];
        $types = '';

        foreach ($where as $column => $value) {
            $conditions[] = "$column = ?";
            $params[] = $value;
            $types .= is_int($value) ? 'i' : 's';
        }

        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $conditions);
        $stmt = $this->db->query($sql, $params, $types);
        return $this->db->fetchAll($stmt);
    }

    /**
     * Criar novo registro
     * @param array $data ['coluna' => 'valor', ...]
     */
    public function create($data)
    {
        if (!$this->table) {
            throw new \Exception("Tabela não definida no model");
        }

        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        $values = array_values($data);
        $types = $this->getTypes($values);

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->db->query($sql, $values, $types);

        if ($stmt && $this->db->affectedRows() > 0) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Atualizar registro
     * @param int $id ID do registro
     * @param array $data ['coluna' => 'valor', ...]
     */
    public function update($id, $data)
    {
        if (!$this->table) {
            throw new \Exception("Tabela não definida no model");
        }

        $sets = [];
        $params = [];
        $types = '';

        foreach ($data as $column => $value) {
            $sets[] = "$column = ?";
            $params[] = $value;
            $types .= is_int($value) ? 'i' : 's';
        }

        $params[] = $id;
        $types .= 'i';

        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = ?";
        $stmt = $this->db->query($sql, $params, $types);

        return $stmt && $this->db->affectedRows() > 0;
    }

    /**
     * Deletar registro
     */
    public function delete($id)
    {
        if (!$this->table) {
            throw new \Exception("Tabela não definida no model");
        }

        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->query($sql, [$id], 'i');

        return $stmt && $this->db->affectedRows() > 0;
    }

    /**
     * Determina tipos de parâmetros para bind
     */
    protected function getTypes($values)
    {
        $types = '';
        foreach ($values as $value) {
            if (is_int($value)) {
                $types .= 'i';
            } elseif (is_float($value)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        return $types;
    }

    /**
     * Conta registros
     */
    public function count()
    {
        if (!$this->table) {
            throw new \Exception("Tabela não definida no model");
        }

        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $result = $this->db->fetchOne($stmt);
        return $result['total'] ?? 0;
    }
}

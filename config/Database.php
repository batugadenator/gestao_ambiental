<?php

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $this->loadEnv();
        $this->connect();
    }

    /**
     * Carrega variáveis do arquivo .env
     */
    private function loadEnv()
    {
        $envFile = $_SERVER['DOCUMENT_ROOT'] . '/.env';
        
        if (!file_exists($envFile)) {
            die('Arquivo .env não encontrado. Copie .env.example para .env');
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
            }
        }
    }

    /**
     * Conecta ao banco de dados
     */
    private function connect()
    {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $user = $_ENV['DB_USER'] ?? '';
        $pass = $_ENV['DB_PASS'] ?? '';
        $db = $_ENV['DB_NAME'] ?? '';
        $charset = $_ENV['DB_CHARSET'] ?? 'utf8';

        $this->connection = new mysqli($host, $user, $pass, $db);

        if ($this->connection->connect_error) {
            error_log("Erro de conexão: " . $this->connection->connect_error);
            die('Erro ao conectar ao banco de dados');
        }

        $this->connection->set_charset($charset);
    }

    /**
     * Singleton: retorna instância única da conexão
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retorna a conexão MySQLi
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Executa query com prepared statement
     * @param string $sql SQL com placeholders (?)
     * @param array $params Valores para bind
     * @param string $types Tipos: 's' (string), 'i' (int), 'd' (double)
     * @return array|false Dados ou false se erro
     */
    public function query($sql, $params = [], $types = '')
    {
        try {
            $stmt = $this->connection->prepare($sql);

            if (!$stmt) {
                throw new Exception("Erro ao preparar query: " . $this->connection->error);
            }

            // Se houver parâmetros, faz bind
            if (!empty($params)) {
                if (empty($types)) {
                    // Auto-detecta tipos se não informado
                    $types = str_repeat('s', count($params));
                }
                $stmt->bind_param($types, ...$params);
            }

            if (!$stmt->execute()) {
                throw new Exception("Erro ao executar query: " . $stmt->error);
            }

            return $stmt;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Fetch all results
     */
    public function fetchAll($stmt)
    {
        if (!$stmt || !($stmt instanceof mysqli_stmt)) {
            return [];
        }

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Fetch single row
     */
    public function fetchOne($stmt)
    {
        if (!$stmt || !($stmt instanceof mysqli_stmt)) {
            return null;
        }

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Retorna ID do último insert
     */
    public function lastInsertId()
    {
        return $this->connection->insert_id;
    }

    /**
     * Retorna número de rowsafetadas
     */
    public function affectedRows()
    {
        return $this->connection->affected_rows;
    }

    /**
     * Fecha conexão
     */
    public function close()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}

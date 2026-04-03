<?php

// Carrega a classe Database
require_once __DIR__ . '/Database.php';

/**
 * Função legacy para compatibilidade com código antigo
 * Recomendado usar Database::getInstance()->getConnection() diretamente
 */
function connect_local_mysqli($database = NULL, $charset = "utf8")
{
    return Database::getInstance()->getConnection();
}

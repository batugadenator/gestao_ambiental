<?php

/**
 * Autoloader para classes da aplicação
 * Mapeia namespaces para diretórios
 */
spl_autoload_register(function ($class) {
    // Namespaces da aplicação
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';

    // Verificar se a classe usa o prefixo esperado
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Obter o caminho relativo da classe
    $relative_class = substr($class, $len);

    // Converter namespace para caminho do arquivo
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Se o arquivo existe, incluí-lo
    if (file_exists($file)) {
        require $file;
    }
});

/**
 * Autoloader para config
 */
spl_autoload_register(function ($class) {
    $prefix = 'config\\';
    $base_dir = __DIR__ . '/config/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = 'resende123';
$dbName = 'gestao_ambiental';

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($mysqli->connect_error) {
    die("Erro de conexão: " . $mysqli->connect_error);
}

// Nome do arquivo de backup
$backupFile = 'backup_' . $dbName . '_' . date("Y-m-d_H-i-s") . '.sql';

// Abre o arquivo para escrita
$handle = fopen($backupFile, 'w');

if ($handle === false) {
    die("Erro ao criar o arquivo de backup.");
}

// Exporta cada tabela
$result = $mysqli->query("SHOW TABLES");

while ($row = $result->fetch_row()) {
    $table = $row[0];

    // Cria a estrutura da tabela
    $createTableResult = $mysqli->query("SHOW CREATE TABLE $table");
    $createTableRow = $createTableResult->fetch_row();
    fwrite($handle, "\n\n" . $createTableRow[1] . ";\n\n");

    // Exporta os dados da tabela
    $tableData = $mysqli->query("SELECT * FROM $table");
    while ($data = $tableData->fetch_assoc()) {
        $values = array_map([$mysqli, 'real_escape_string'], array_values($data));
        $values = "'" . implode("', '", $values) . "'";
        $columns = implode(", ", array_keys($data));
        $insertQuery = "INSERT INTO $table ($columns) VALUES ($values);\n";
        fwrite($handle, $insertQuery);
    }
}

// Fecha o arquivo
fclose($handle);
$mysqli->close();

echo "Backup manual criado com sucesso em: $backupFile";
?>

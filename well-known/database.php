<?php
header('Content-type: text/html; charset=UTF-8');

// Credenciais vêm de variáveis de ambiente (Docker, cPanel SetEnv, etc).
// Em ambientes onde env não está disponível, define-se em config.local.php
// (gitignored — copie config.local.php.example).
$server   = getenv('DB_HOST') ?: null;
$username = getenv('DB_USER') ?: null;
$password = getenv('DB_PASS') ?: null;
$database = getenv('DB_NAME') ?: null;

if (!$server || !$username || !$database) {
    $local = __DIR__ . '/config.local.php';
    if (is_file($local)) {
        require $local;
    }
}

if (!$server || !$username || !$database) {
    die('Configuração de banco ausente. Defina DB_HOST/DB_USER/DB_PASS/DB_NAME via env ou crie config.local.php a partir de config.local.php.example.');
}

try {
    $conn = new PDO(
        "mysql:host=$server;dbname=$database;",
        $username,
        $password,
        [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die('Falha na conexão com o banco de dados: ' . $e->getMessage());
}

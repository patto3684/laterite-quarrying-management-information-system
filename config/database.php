<?php
function getConnection(): PDO {
    static $pdo = null;
    static $initialized = false;

    if ($pdo === null) {
        $user = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: '';
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3306';
        $dbname = getenv('DB_NAME') ?: 'lqsmis';

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $baseDsn = "mysql:host={$host};port={$port};charset=utf8mb4";
        try {
            // Connect to server (no db) first to ensure database exists when possible
            $pdo = new PDO($baseDsn, $user, $password, $options);
            try {
                $pdo->exec("CREATE DATABASE IF NOT EXISTS {$dbname} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            } catch (PDOException $inner) {
                // ignore creation errors for managed databases where user lacks privileges
            }
            $pdo = new PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4", $user, $password, $options);
        } catch (PDOException $e) {
            $msg = 'Could not connect to MySQL: ' . $e->getMessage() .
                "\nEnsure the database server is running and the environment variables DB_HOST, DB_PORT, DB_USER, DB_PASSWORD are set correctly.";
            throw new PDOException($msg, (int)$e->getCode());
        }
    }

    if (!$initialized) {
        $schema = file_get_contents(__DIR__ . '/../setup.sql');
        if ($schema !== false) {
            $pdo->exec($schema);
        }
        $initialized = true;
    }

    return $pdo;
}

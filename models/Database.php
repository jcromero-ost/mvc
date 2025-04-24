<?php
require_once __DIR__ . '/../config/config.php';

class Database {
    public static function connect() {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            return new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }
}

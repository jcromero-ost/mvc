<?php

class DatabaseXGEST {
    public static function connect() {
        $host = 'localhost';
        $db = 'xgestevo';
        $user = 'crm';
        $pass = 'HajtKaVwd5Dkd2D';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        return new PDO($dsn, $user, $pass, $options);
    }
}

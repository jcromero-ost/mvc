<?php

class DatabaseXGEST {
    public static function connect() {
        $host = '192.168.51.151';
        $port = 3307;
        $db = 'xgestevo';
        $user = 'XGESTEVO';
        $pass = 'XGESTEVO';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        return new PDO($dsn, $user, $pass, $options);
    }
}

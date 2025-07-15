<?php
// config/config_xgest.php

$XGEST_DB_HOST = '192.168.51.151:3307'; // o IP si es externa
$XGEST_DB_NAME = 'xgestevo';
$XGEST_DB_USER = 'XGESTEVO';
$XGEST_DB_PASS = 'XGESTEVO';
$XGEST_DB_CHARSET = 'utf8mb4';

try {
    $pdo_xgest = new PDO(
        "mysql:host=$XGEST_DB_HOST;dbname=$XGEST_DB_NAME;charset=$XGEST_DB_CHARSET",
        $XGEST_DB_USER,
        $XGEST_DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo conectar a la base de datos de Xgest', 'detalle' => $e->getMessage()]);
    exit;
}

<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start(); 

$view = './contratos_laborales_content.php';
include __DIR__ . '/layout.php';

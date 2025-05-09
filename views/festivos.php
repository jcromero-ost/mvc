<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start(); 

$view = './festivos_content.php';
include __DIR__ . '/layout.php';

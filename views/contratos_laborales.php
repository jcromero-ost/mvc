<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start(); 

require_once(__DIR__ . '/../models/unauth.php');

$view = './contratos_laborales_content.php';
include __DIR__ . '/layout.php';

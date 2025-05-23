<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start(); 

require_once(__DIR__ . '/../models/unauth.php');

$view = './festivos_content.php';
include __DIR__ . '/layout.php';

<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start(); 

require_once(__DIR__ . '/../models/Departamento.php');

require_once(__DIR__ . '/../models/unauth.php');

$departamentoModel = new Departamento();
$departamentos = $departamentoModel->getAll();

$view = './crear_usuario_content.php';
include __DIR__ . '/layout.php';

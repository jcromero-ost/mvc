<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start(); 

require_once(__DIR__ . '/../models/Departamento.php');

$departamentoModel = new Departamento();
$departamentos = $departamentoModel->getAll();

$view = './crear_ticket_content.php';
include __DIR__ . '/layout.php';

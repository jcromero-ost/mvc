<?php
require_once(__DIR__ . '/../models/Departamento.php');
$departamentoModel = new Departamento();
$departamentos = $departamentoModel->getAll();

$view = './departamentos_content.php';
include __DIR__ . '/layout.php';

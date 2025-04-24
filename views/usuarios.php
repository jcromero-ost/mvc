<?php
 
if (session_status() === PHP_SESSION_NONE) 
session_start(); 

require_once(__DIR__ . '/../models/Usuario.php');

$usuarioModel = new Usuario();
$usuarios = $usuarioModel->getAllUsuarios();

$view = 'usuarios_content.php';
include __DIR__ . '/layout.php';

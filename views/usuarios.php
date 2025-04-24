<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargar el modelo Usuario
require_once(__DIR__ . '/../models/Usuario.php');

// Instancia del modelo
$usuarioModel = new Usuario();
$usuarios = $usuarioModel->getAllUsuarios(); // Asegúrate que esta función existe

// Define la vista interna que se va a incluir dentro del layout
$view = './usuarios_content.php';

// Carga layout principal
include __DIR__ . '/layout.php';

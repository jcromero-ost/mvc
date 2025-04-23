<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    public function index() {
        $usuarios = Usuario::all();
        require_once __DIR__ . '/../views/usuarios.php';
    }

    public function create() {
        require_once __DIR__ . '/../views/crear_usuario.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Usuario::create($_POST['nombre'], $_POST['email'], $_POST['password']);
            header("Location: index.php?r=usuarios");
        }
    }
}

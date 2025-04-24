<?php
require_once 'models/auth.php';

class AuthController {

    public function login() {
       
        if (session_status() === PHP_SESSION_NONE) 
        session_start(); 


        // Validar entrada básica
        $email = trim($_POST['user'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Debes completar todos los campos.';
            header('Location: /login');
            exit;
        }

        $auth = new Auth();
        $user = $auth->authenticate($email, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            $_SESSION['success'] = 'Bienvenido, ' . htmlspecialchars($user['nombre'] ?? '');
            header('Location: /dashboard');
        } else {
            $_SESSION['error'] = 'Correo o contraseña incorrectos.';
            header('Location: /login');
        }
        exit;
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login');
        exit;
    }
}

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'models/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['user'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Completa todos los campos.'];
        header('Location: views/login.php');
        exit();
    }

    try {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_rol'] = $user['rol'];

            header("Location: views/dashboard.php");
            exit();
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Credenciales inválidas.'];
            header("Location: views/login.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Error en la base de datos.'];
        header("Location: views/login.php");
        exit();
    }
} else {
    header("Location: views/login.php");
    exit();
}

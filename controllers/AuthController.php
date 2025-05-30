<?php
require_once 'models/auth.php';

class AuthController {

    public function login() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        unset($_SESSION['tablet_mode']);

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
            $this->iniciarSesion($user, false);
            header('Location: /registro_horario');
        } else {
            $_SESSION['error'] = 'Correo o contraseña incorrectos.';
            header('Location: /login');
        }

        exit;
    }

    public function loginTabletPin() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $usuarioId = $_POST['usuario_id'] ?? null;
        $pin = $_POST['pin'] ?? null;

        if (!$usuarioId || !$pin) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos.']);
            exit;
        }

        if (Usuario::verificarPin($usuarioId, $pin)) {
            // Obtener datos del usuario
            $db = Database::connect();
            $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id");
            $stmt->bindParam(':id', $usuarioId);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $this->iniciarSesion($user, true);
                echo json_encode(['success' => true, 'redirect' => '/registro_horario']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Usuario no encontrado.']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'PIN incorrecto.']);
        }

        exit;
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login');
        exit;
    }

    private function iniciarSesion($user, $modoTablet = false) {
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['id'] = $user['id'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['alias'] = $user['alias'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['telefono'] = $user['telefono'];
        $_SESSION['foto'] = $user['foto'] ?? null;
        $_SESSION['dept'] = $user['departamento_id'] ?? null;
        $_SESSION['tablet_mode'] = $modoTablet;
        $_SESSION['success'] = 'Bienvenido, ' . htmlspecialchars($user['nombre'] ?? '');
        session_write_close();
    }
}

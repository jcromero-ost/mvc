<?php
require_once __DIR__ . '/../models/PerfilUser.php';

class PerfilUserController
{
    public function index()
    {
        include 'views/perfil_user/perfil_user.php';
    }

    public function actualizarPerfil()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['id'];
            $nombre = $_POST['nombre'] ?? '';
            $apellidos = $_POST['apellidos'] ?? '';
            $email = $_POST['email'] ?? '';
            $foto = $_POST['foto_recortada'] ?? null;

            if (!$nombre || !$email) {
                $_SESSION['error'] = 'Nombre y email son obligatorios.';
                header('Location: /perfil_usuario.php');
                exit;
            }

            $actualizado = PerfilUser::actualizarPerfil($id, $nombre, $apellidos, $email, $foto);

            $_SESSION[$actualizado ? 'success' : 'error'] = $actualizado
                ? 'Perfil actualizado correctamente.'
                : 'No se pudo actualizar el perfil.';

            header('Location: /perfil_usuario.php');
            exit;
        }
    }

    public function cambiarContrasena()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['id'];
            $actual = $_POST['actual'] ?? '';
            $nueva = $_POST['nueva'] ?? '';
            $repetir = $_POST['repetir'] ?? '';

            if (!$actual || !$nueva || $nueva !== $repetir) {
                $_SESSION['error'] = 'Verifica los campos de la contraseña.';
                header('Location: /perfil_usuario.php');
                exit;
            }

            if (!PerfilUser::verificarContrasena($id, $actual)) {
                $_SESSION['error'] = 'Contraseña actual incorrecta.';
                header('Location: /perfil_usuario.php');
                exit;
            }

            $cambiada = PerfilUser::cambiarContrasena($id, $nueva);

            $_SESSION[$cambiada ? 'success' : 'error'] = $cambiada
                ? 'Contraseña actualizada correctamente.'
                : 'No se pudo actualizar la contraseña.';

            header('Location: /perfil_usuario.php');
            exit;
        }
    }

    public function cambiarPin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['id'];
            $pin = $_POST['pin'] ?? '';
            $pin_repetir = $_POST['pin_repetir'] ?? '';

            if (!$pin || $pin !== $pin_repetir) {
                $_SESSION['error'] = 'El PIN no coincide o está vacío.';
                header('Location: /perfil_usuario.php');
                exit;
            }

            $pin_sha256 = hash('sha256', $pin);
            $actualizado = PerfilUser::actualizarPin($id, $pin_sha256);

            $_SESSION[$actualizado ? 'success' : 'error'] = $actualizado
                ? 'PIN actualizado correctamente.'
                : 'Error al actualizar el PIN.';

            header('Location: /perfil_usuario.php');
            exit;
        }
    }
}

// Enrutamiento simple desde POST
if (isset($_POST['accion'])) {
    $controller = new PerfilUserController();

    switch ($_POST['accion']) {
        case 'actualizar_perfil':
            $controller->actualizarPerfil();
            break;
        case 'cambiar_contrasena':
            $controller->cambiarContrasena();
            break;
        case 'cambiar_pin':
            $controller->cambiarPin();
            break;
    }
}

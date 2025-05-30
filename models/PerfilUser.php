<?php
require_once(__DIR__ . '/Database.php');

class PerfilUser {
    public static function obtenerPorId($id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function actualizarPerfil($id, $nombre, $apellidos, $email, $foto = null) {
        $db = Database::connect();
        $sql = "UPDATE usuarios SET nombre = ?, apellidos = ?, email = ?";
        $params = [$nombre, $apellidos, $email];

        if ($foto && strpos($foto, 'data:image') === 0) {
            $sql .= ", foto = ?";
            $params[] = $foto;
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public static function verificarContrasena($id, $passwordActual) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT password FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            return password_verify($passwordActual, $usuario['password']);
        }

        return false;
    }

    public static function cambiarContrasena($id, $nueva) {
        $db = Database::connect();
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    public static function actualizarPin($id, $pinSha256) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE usuarios SET pin = ? WHERE id = ?");
        return $stmt->execute([$pinSha256, $id]);
    }
}

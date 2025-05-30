<?php
require_once __DIR__ . '/Database.php';

class Auth {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function authenticate($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Opcional: podrías aquí regenerar la sesión o registrar logs
            return $user;
        }

        return false;
    }

    public function authenticateByIdAndPin($userId, $pin) {
    $pdo = Database::connect();
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($pin, $user['password'])) {
        return $user;
    }
    return false;
}

}

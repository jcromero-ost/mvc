<?php
require_once 'models/Database.php';

try {
    $db = Database::connect();
    echo "✅ Conexión a la base de datos exitosa.<br>";

    // Listar usuarios de prueba
    $stmt = $db->query("SELECT id, email FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($usuarios) {
        echo "👤 Usuarios encontrados:<br><ul>";
        foreach ($usuarios as $user) {
            echo "<li>ID: {$user['id']} - Email: {$user['email']}</li>";
        }
        echo "</ul>";
    } else {
        echo "⚠️ La tabla 'usuarios' está vacía.";
    }

} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}

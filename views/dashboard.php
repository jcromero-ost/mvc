<?php
require_once '../session.php';
require_once 'components/head.php';
require_once 'components/header.php';
require_once 'components/nav.php';
?>

<main class="container mt-4">
    <div class="card shadow-sm p-4">
        <h2 class="mb-3">Bienvenido, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
        <p class="text-muted">Has iniciado sesión como <strong><?= $_SESSION['user_rol'] ?></strong>.</p>

        <hr>

        <h4>Panel de inicio</h4>
        <div class="list-group">
            <a href="index.php?r=usuarios" class="list-group-item list-group-item-action">👥 Gestionar usuarios</a>
            <a href="index.php?r=crear_usuario" class="list-group-item list-group-item-action">➕ Crear nuevo usuario</a>
            <a href="#" class="list-group-item list-group-item-action">⚙️ Acciones según tu rol</a>
        </div>
    </div>
</main>


<?php require_once 'components/footer.php'; ?>

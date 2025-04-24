<?php
require_once __DIR__ . '/../session.php';
require_once 'components/head.php';
require_once 'components/header.php';
require_once 'components/nav.php';
?>

<main class="container mt-4">
  <div class="card shadow-sm p-4">
    <h2 class="mb-3">
      Bienvenido, <?= htmlspecialchars($_SESSION['user']['nombre'] ?? 'Usuario') ?>!
    </h2>
    <p class="text-muted">
      Has iniciado sesión como <strong><?= htmlspecialchars($_SESSION['user']['rol'] ?? 'Desconocido') ?></strong>.
    </p>

    <hr>

    <h4 class="mt-4">Panel de inicio</h4>
    <div class="list-group">
      <a href="/usuarios" class="list-group-item list-group-item-action">
        👥 Gestionar usuarios
      </a>
      <a href="/crear_usuario" class="list-group-item list-group-item-action">
        ➕ Crear nuevo usuario
      </a>
      <a href="#" class="list-group-item list-group-item-action">
        ⚙️ Acciones según tu rol
      </a>
    </div>
  </div>
</main>

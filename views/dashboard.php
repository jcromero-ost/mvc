<?php
require_once __DIR__ . '/../session.php';
require_once 'components/head.php';
require_once 'components/header.php';
require_once 'components/nav.php';
?>

<main class="container mt-4">
  <div class="card shadow-sm p-4">
    <h1 class="mb-3">
      Bienvenido, <?= htmlspecialchars($_SESSION['user']['nombre'] ?? 'Usuario') ?>!
    </h1>
    
</main>

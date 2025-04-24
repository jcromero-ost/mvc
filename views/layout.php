<?php
// Protección de sesión para todas las vistas privadas
require_once __DIR__ . '/../session.php';
?>
<!DOCTYPE html>
<html lang="es">
  <?php include __DIR__ . '/components/head.php'; ?>

  <body>

    <?php include __DIR__ . '/components/header.php'; ?>
    <?php include __DIR__ . '/components/nav.php'; ?>

    <main style="margin-left: 220px; padding: 1rem;">
      <?php
        // Cargar alertas globales
        include __DIR__ . '/components/alerts.php';

        // Cargar vista específica
        if (isset($view)) {
          $viewFile = __DIR__ . '/' . $view;
          if (file_exists($viewFile)) {
            include $viewFile;
          } else {
            echo "<div class='alert alert-danger'>Vista '$view' no encontrada.</div>";
          }
        } else {
          echo "<div class='alert alert-warning'>Error: no se especificó ninguna vista.</div>";
        }
      ?>
    </main>

    <?php // Opcional: footer si lo agregás después ?>
    <?php // include __DIR__ . '/components/footer.php'; ?>

  </body>
</html>

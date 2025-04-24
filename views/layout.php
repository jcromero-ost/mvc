<?php
// layout.php - plantilla base
?>

<!DOCTYPE html>
<html lang="es">
  <?php include __DIR__ . '/components/head.php'; ?>
  
<body>

  <?php include __DIR__ . '/components/header.php'; ?>
  <?php include __DIR__ . '/components/nav.php'; ?>

  <main style="margin-left: 220px; padding: 1rem;">
    <?php
    // Aquí se insertará el contenido específico de cada vista
    if (isset($view)) {
      $viewFile = __DIR__ . '/' . $view;
    if (file_exists($viewFile)) {
      include $viewFile;
    } else {
      echo "<p>Vista '$view' no encontrada.</p>";
    }

    } else {
      echo "<p>Error: no se especificó ninguna vista.</p>";
    }
    ?>
  </main>

  <?php // include __DIR__ . '/components/footer.php'; ?> 

</body>
</html>

<?php
// Protección de sesión para todas las vistas privadas
require_once __DIR__ . '/../session.php';
?>
<!DOCTYPE html>
<html lang="es">
  <?php include __DIR__ . '/components/head.php'; ?>

  <body>

    <?php 
    include __DIR__ . '/components/header.php'; 
    ?>
    <?php if (empty($_SESSION['tablet_mode'])): ?>
      <?php include __DIR__ . '/components/nav.php'; ?>
    <?php endif; ?>

<?php
$esTablet = !empty($_SESSION['tablet_mode']);
$esRegistroHorario = isset($view) && $view === 'registro_horario_content.php';

// Definir clases y estilos dinámicamente
$claseMain = ($esTablet || $esRegistroHorario)
    ? 'container-fluid d-flex justify-content-center align-items-center'
    : 'main-default';

$estiloMain = ($esTablet || $esRegistroHorario) ? 'min-height: calc(100vh - 70px); padding-top: 1rem;' : '';

$claseContenedor = ($esTablet || $esRegistroHorario)
    ? 'w-100'
    : 'wrapper';

$estiloContenedor = ($esTablet || $esRegistroHorario)
    ? 'max-width: 550px;'
    : '';
?>

<main class="<?= $claseMain ?>" style="<?= $estiloMain ?>">
  <div class="<?= $claseContenedor ?>" style="<?= $estiloContenedor ?>">
    <?php
      include __DIR__ . '/components/alerts.php'; 
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
  </div>
</main>

<?php // Opcional: include footer si decides agregarlo ?>
<?php // include __DIR__ . '/components/footer.php'; ?>

</body>
</html>

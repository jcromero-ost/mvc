<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar-osttech">
  <a class="nav-link <?= $current === 'usuarios.php' ? 'active' : '' ?>" href="usuarios.php">
    Usuarios
  </a>
  <a class="nav-link <?= $current === 'logout.php' ? 'active' : '' ?>" href="logout.php">
    Cerrar sesión
  </a>
</div>

<?php
$current = basename($_SERVER['REQUEST_URI']);
?>

<nav class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white position-fixed" style="width: 220px; height: 100vh;">
  <a href="/usuarios" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
    <span class="fs-4">Intranet</span>
  </a>
  <hr>
  <ul class="nav nav-pills flex-column mb-auto">

    <!-- ITEM USUARIOS -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#" id="usuariosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-people-fill me-2"></i>Usuarios
      </a>
      <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="usuariosDropdown">
        <li><a class="dropdown-item" href="/crear_usuario"><i class="bi bi-person-plus me-2"></i>Crear usuario</a></li>
        <li><a class="dropdown-item" href="/usuarios"><i class="bi bi-person-lines-fill me-2"></i>Gestionar usuarios</a></li>
        <li><a class="dropdown-item" href="/departamentos"><i class="bi bi-diagram-3 me-2"></i>Departamentos (roles)</a></li>
      </ul>
    </li>

    <!-- Cerrar sesión -->
    <li class="nav-item mt-3">
      <a class="nav-link text-danger" href="/logout">
        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
      </a>
    </li>

  </ul>
</nav>

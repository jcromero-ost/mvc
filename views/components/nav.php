<?php
$current = basename($_SERVER['REQUEST_URI']);
?>

<nav class="sidebar-osttech d-flex flex-column flex-shrink-0 p-3 bg-dark position-fixed" style="width: 220px; height: 100vh;">
  <a href="/usuarios" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
    <span class="fs-4">Intranet</span>
  </a>
  <hr>
  <ul class="nav nav-pills flex-column mb-auto">

    <!-- MENU CONTROL DE TIEMPO -->
    <li class="nav-item dropdown <?php echo (in_array($current, ['registro_horario', 'informe_registro_horario']) ? 'active' : ''); ?>">
      <a class="nav-link dropdown-toggle <?php echo (in_array($current, ['registro_horario', 'informe_registro_horario']) ? 'active' : ''); ?>" href="#" id="tiempoDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-clock-history me-2"></i>Control de Tiempo
      </a>
      <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="tiempoDropdown">
        <li><a class="dropdown-item" href="/registro_horario"><i class="bi bi-clock me-2"></i>Registro horario</a></li>
        <li><a class="dropdown-item" href="/registro-horario/listado"><i class="bi bi-clipboard-data me-2"></i>Informe de registro horario</a></li>
        <li><a class="dropdown-item" href="/calendario_laboral"><i class="bi bi-calendar3 me-2"></i>Calendario laboral</a></li>
      </ul>
    </li>

    <!-- MENU USUARIOS -->
    <li class="nav-item dropdown <?php echo (in_array($current, ['crear_usuario', 'usuarios', 'departamentos']) ? 'active' : ''); ?>">
      <a class="nav-link dropdown-toggle <?php echo (in_array($current, ['crear_usuario', 'usuarios', 'departamentos']) ? 'active' : ''); ?>" href="#" id="usuariosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-people-fill me-2"></i>Usuarios
      </a>
      <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="usuariosDropdown">
        <li><a class="dropdown-item" href="/crear_usuario"><i class="bi bi-person-plus me-2"></i>Crear usuario</a></li>
        <li><a class="dropdown-item" href="/usuarios"><i class="bi bi-person-lines-fill me-2"></i>Gestionar usuarios</a></li>
        <li><a class="dropdown-item" href="/departamentos"><i class="bi bi-diagram-3 me-2"></i>Departamentos (roles)</a></li>
      </ul>
    </li>

    <!-- MENU CLIENTES -->
    <li class="nav-item dropdown <?php echo (in_array($current, ['crear_cliente', 'clientes']) ? 'active' : ''); ?>">
      <a class="nav-link dropdown-toggle <?php echo (in_array($current, ['crear_cliente', 'clientes']) ? 'active' : ''); ?>" href="#" id="clientesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-square me-2"></i>Clientes
      </a>
      <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="clientesDropdown">
        <li><a class="dropdown-item" href="/crear_cliente"><i class="bi bi-person-plus me-2"></i>Crear cliente</a></li>
        <li><a class="dropdown-item" href="/clientes"><i class="bi bi-person-vcard me-2"></i>Gestionar clientes</a></li>
      </ul>
    </li>

    <!-- MENU TICKETS -->
    <li class="nav-item dropdown <?php echo (in_array($current, ['crear_ticket', 'tickets_pendientes', 'tickets']) ? 'active' : ''); ?>">
      <a class="nav-link dropdown-toggle <?php echo (in_array($current, ['crear_ticket', 'tickets_pendientes', 'tickets']) ? 'active' : ''); ?>" href="#" id="ticketsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-card-checklist me-2"></i>Tickets
      </a>
      <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="ticketsDropdown">
        <li><a class="dropdown-item" href="/crear_ticket"><i class="bi bi-clipboard-plus me-2"></i>Crear ticket</a></li>
        <li><a class="dropdown-item" href="/tickets_pendientes"><i class="bi bi-view-list me-2"></i>Tickets pendientes</a></li>
        <li><a class="dropdown-item" href="/tickets"><i class="bi bi-view-stacked me-2"></i>Lista de tickets</a></li>
      </ul>
    </li>

    

    <!-- CERRAR SESIÓN -->
    <li class="nav-item mt-3">
      <a class="nav-link text-danger d-flex align-items-center" href="/logout">
        <i class="bi bi-box-arrow-right text-danger me-2"></i> Cerrar sesión
      </a>
    </li>

  </ul>
</nav>

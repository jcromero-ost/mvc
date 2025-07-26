<?php
$current = basename($_SERVER['REQUEST_URI']);
?>
  <button id="toggleSidebar" class="btn btn-dark d-lg-none" style="position: fixed; top: 82px; left: 10px; z-index: 1050;">
  <i class="bi bi-list" style="font-size: 1.8rem;"></i>
</button>
<nav class="sidebar-osttech d-flex flex-column flex-shrink-0 p-3 bg-dark position-fixed" style="width: 220px; height: 100vh;">
  <hr>
  <ul class="nav nav-pills flex-column mb-auto"> 
    <a href="/tickets_pendientes" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <span class="fs-4">Intranet</span>
    </a>
    <!-- MENU CONTROL DE TIEMPO -->
    <li class="nav-item dropdown <?php echo (in_array($current, ['registro_horario', 'listado', 'calendario_laboral', 'festivos', 'resumen-horas']) ? 'active' : ''); ?>"data-ignore-click-hide>
      <a class="nav-link dropdown-toggle <?php echo (in_array($current, ['registro_horario', 'listado', 'calendario_laboral', 'festivos', 'resumen-horas']) ? 'active' : ''); ?>" href="#" id="tiempoDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-ignore-click-hide>
        <i class="bi bi-clock-history me-2"></i>Control de Tiempo
      </a>
      <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="tiempoDropdown">
        <li><a class="dropdown-item" href="/registro_horario"><i class="bi bi-clock me-2"></i>Registro horario</a></li>
        <li><a class="dropdown-item" href="/registro-horario/listado"><i class="bi bi-clipboard-data me-2"></i>Informe de registro horario</a></li>
          <li><a class="dropdown-item" href="/vacaciones"><i class="bi bi-airplane me-2"></i>Solicitar Vacaciones</a></li>
        <?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] == 2 || $_SESSION['dept'] == 3)): ?>
          <li><a class="dropdown-item" href="/calendario_laboral"><i class="bi bi-calendar3 me-2"></i>Calendario laboral</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] == 2 || $_SESSION['dept'] == 3)): ?>
          <li><a class="dropdown-item" href="/festivos"><i class="bi bi-calendar-week me-2"></i>Form festivos</a></li>
        <?php endif; ?>
          <li><a class="dropdown-item" href="/resumen-horas"><i class="bi bi-hourglass-split me-2"></i>Resumen Horas</a></li>
      </ul>
    </li>

    <!-- MENU USUARIOS -->
    <?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] == 2 || $_SESSION['dept'] == 3)): ?>
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
    <?php endif; ?>

    <!-- MENU CLIENTES -->
    <?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] == 2 || $_SESSION['dept'] == 3)): ?>
      <li class="nav-item dropdown <?php echo (in_array($current, ['crear_cliente', 'clientes', 'clientes_historial']) ? 'active' : ''); ?>">
        <a class="nav-link dropdown-toggle <?php echo (in_array($current, ['crear_cliente', 'clientes', 'clientes_historial']) ? 'active' : ''); ?>" href="#" id="clientesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-square me-2"></i>Clientes
        </a>
        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="clientesDropdown">
          <li><a class="dropdown-item" href="/crear_cliente"><i class="bi bi-person-plus me-2"></i>Crear cliente</a></li>
          <li><a class="dropdown-item" href="/clientes"><i class="bi bi-person-vcard me-2"></i>Gestionar clientes</a></li>
          <li><a class="dropdown-item" href="/clientes_historial"><i class="bi bi-list-columns-reverse me-2"></i>Historial clientes</a></li>
        </ul>
      </li>
    <?php endif; ?>

    <!-- MENU INFRAESTRUCTURA -->
    <?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] == 1 || $_SESSION['dept'] == 2 || $_SESSION['dept'] == 3)): ?>
      <li class="nav-item dropdown <?php echo (in_array($current, ['crear_contrato', 'contratos', 'servicios', 'fichas']) ? 'active' : ''); ?>">
        <a class="nav-link dropdown-toggle <?php echo (in_array($current, ['crear_contrato', 'contratos', 'servicios', 'fichas']) ? 'active' : ''); ?>" href="#" id="infraestructuraDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-hdd-network me-2"></i>Infraestructura
        </a>
        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="infraestructuraDropdown">
          <!-- CONTRATOS -->
          <li><h6 class="dropdown-header">Contratos</h6></li>
          <li><a class="dropdown-item" href="/crear_contrato"><i class="bi bi-file-plus me-2"></i>Crear contrato</a></li>
          <li><a class="dropdown-item" href="/contratos-clientes"><i class="bi bi-file-earmark-text me-2"></i>Listar contratos</a></li>

          <!-- SERVICIOS -->
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="/servicios"><i class="bi bi-diagram-3-fill me-2"></i>Servicios</a></li>

          <!-- FICHAS -->
          <li><a class="dropdown-item" href="/fichas"><i class="bi bi-pc-display-horizontal me-2"></i>Fichas</a></li>
        </ul>
      </li>
    <?php endif; ?>


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

    <!-- MENU CONFIGURACIÓN -->
    <?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] == 2 || $_SESSION['dept'] == 3)): ?>
      <li class="nav-item dropdown <?php echo (in_array($current, ['contratos_laborales']) ? 'active' : ''); ?>">
        <a class="nav-link dropdown-toggle <?php echo (in_array($current, ['contratos_laborales']) ? 'active' : ''); ?>" href="#" id="configuracionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-gear me-2"></i>Configuración
        </a>
        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="configuracionDropdown">
          <li><a class="dropdown-item" href="/contratos_laborales"><i class="bi bi-newspaper me-2"></i>Contratos Laborales</a></li>
        </ul>
      </li>
    <?php endif; ?>

    <!-- CCrear_pedido Test -->
    <li class="nav-item mt-3">
      <a class="nav-link text-primary d-flex align-items-center" href="/xgest/pedidos-test">
        <i class="bi bi-box-arrow-right text-danger me-2"></i> crear pedido test
      </a>
    </li>

  </ul>
</nav>
<script>
  const toggleBtn = document.getElementById('toggleSidebar');
  const sidebar = document.querySelector('nav.sidebar-osttech');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('show');
  });

  // Opcional: cerrar si clic fuera
  document.addEventListener('click', (e) => {
    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target) && sidebar.classList.contains('show')) {
      sidebar.classList.remove('show');
    }
  });
</script>

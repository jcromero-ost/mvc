<header class="bg-white border-bottom shadow-sm py-3 px-4 d-flex align-items-center sticky-top" style="z-index: 1050;">
  <div class="d-flex justify-content-between align-items-center w-100">
    <img src="/public/images/osttech.png" alt="Logo Osttech" style="height: 40px;">
    <div class="d-flex align-items-center justify-content-center" style="gap: 0.5rem;">

      <!-- Notificaciones -->
      <div class="dropdown">
        <button class="btn btn-primary rounded position-relative me-2 dropdown-toggle btn-sm" type="button" id="boton_notificaciones" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-bell-fill text-white"></i>
          <span id="bola_notificaciones" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle d-none"></span>
          <span id="bola_notificaciones_vacaciones" class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle d-none"></span>
        </button>

          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="boton_notificaciones">
              <?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] == 2 || $_SESSION['dept'] == 3)): ?>
                <li><h1 class="dropdown-header">Solicitudes de Vacaciones</h1></li>
                <li id="lista_vacaciones"></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-center" href="./vacaciones">Ver todas las Vacaciones</a></li>
                <li><hr class="dropdown-divider"></li>
              <?php endif; ?>

              <li><h1 class="dropdown-header">Tickets</h1></li>
              <li>
                <div id="lista_tickets" class="px-2 overflow-auto" style="max-height: 200px;"></div>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-center" href="./tickets_pendientes">Ver todos los Tickets</a></li>
          </ul>
      </div>

      <!-- Nombre del usuario -->
      <p class="text-black me-2 mb-0">Hola, <strong><?php echo $_SESSION['nombre']; ?></strong></p>

      <!-- Foto de perfil con borde primary y enlace -->
      <a href="/perfil_user" class="d-inline-block rounded-circle border border-primary" style="height: 40px; width: 40px; overflow: hidden;">
        <?php if (!empty($_SESSION['foto']) && strpos($_SESSION['foto'], 'data:image') === 0): ?>
          <img src="<?= htmlspecialchars($_SESSION['foto']) ?>" 
               alt="Foto de perfil" 
               style="height: 100%; width: 100%; object-fit: cover;">
        <?php else: ?>
          <img src="/public/images/default.jpeg" 
               alt="Foto por defecto" 
               style="height: 100%; width: 100%; object-fit: cover;">
        <?php endif; ?>
      </a>

      <!-- Logout -->
      <a class="btn btn-dark d-flex align-items-center text-white btn-sm rounded" href="/logout" style="gap: 0.5rem;">
        <i class="bi bi-box-arrow-right"></i>
      </a>

    </div>
  </div>
</header>

<?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] == 2 || $_SESSION['dept'] == 3)): ?>
  <script src="/public/js/notificaciones/notificaciones_vacaciones.js"></script>
<?php endif; ?>

  <script src="/public/js/notificaciones/notificaciones_tickets.js"></script>

<!-- Modal de revisión de vacaciones -->
<?php include_once __DIR__ . '../../components/modal_vacaciones/modal_revisar_solicitud.php'; ?>
<?php include_once __DIR__ . '../../components/modal_vacaciones/modal_rechazar_solicitud.php'; ?>

<?php include_once __DIR__ . '../../components/modal_tickets/modal_asignar_ticket.php'; ?>

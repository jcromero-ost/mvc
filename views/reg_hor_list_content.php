<div class="mb-4">
<div id="mensaje" class="text-success mt-2 d-none alert" role="alert"></div>
<h1 id="titulo-registros">Listado de Registros Horarios</h1>

</div>

<div class="card p-4 mb-4">
<div class="row align-items-end">
<div class="col-md-3 position-relative d-none">
  <label for="usuario" class="form-label">Usuario</label>
  <input type="text" id="usuario-buscador" class="form-control" placeholder="Buscar usuario...">
  <input type="hidden" id="usuario">
  <div id="sugerencias-usuarios" class="list-group"></div>
</div>



  <div class="col-md-4">
    <label for="fecha_desde" class="form-label">Fecha desde</label>
    <input type="date" id="fecha_desde" class="form-control">
  </div>

  <div class="col-md-4">
    <label for="fecha_hasta" class="form-label">Fecha hasta</label>
    <input type="date" id="fecha_hasta" class="form-control">
  </div>

  <div class="col-md-4 d-flex align-items-center">
  <div class="me-auto ms-4">
    <label for="cantidad" class="form-label">Registros por página</label>
    <select id="cantidad" class="form-select">
      <option value="10" selected>10</option>
      <option value="25">25</option>
      <option value="50">50</option>
      <option value="100">100</option>
    </select>
  </div>
  <div class="ms-2 align-self-end">
    <button id="btn-filtrar" class="btn btn-primary btn-sm-custom">
      <i class="bi bi-funnel"></i> Filtrar
    </button>
  </div>
  <div class="ms-2 align-self-end">
    <button id="btn-imprimir" class="btn btn-secondary btn-sm-custom">
      <i class="bi bi-printer"></i> Imprimir
    </button>
  </div>
</div>

</div>



  <div class="table-responsive">
    <table class="table table-bordered table-striped" id="tabla-registros">
      <thead class="text-center align-middle">
        <tr>
          <th>Usuario</th>
          <th>Fecha</th>
          <th>Hora Inicio</th>
          <th>Hora Fin</th>
          <th class="descansos-header">Descansos</th>
          <?php if (isset($_SESSION['dept']) && ($_SESSION['dept'] == 2 || $_SESSION['dept'] == 3)): ?>
              <th>Acciones</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <!-- Se llenará dinámicamente -->
      </tbody>
    </table>

    <nav class="mt-3">
      <ul class="pagination justify-content-center" id="paginacion">
        <!-- Botones de paginación dinámicos -->
      </ul>
    </nav>
  </div>
</div>

<!-- Modal Descansos -->
<div class="modal fade" id="modalDescansos" tabindex="-1" aria-labelledby="modalDescansosLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDescansosLabel">Detalle de Descansos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="contenido-descansos">
        <!-- Listado de descansos dinámico -->
      </div>
    </div>
  </div>
</div>

<!-- Modal confirmación de edicion -->
<div class="modal fade" id="modalEditarRegistroHorario" tabindex="-1" aria-labelledby="modalEditarRegistroHorarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="/controllers/TicketController.php">
        <input type="hidden" name="accion" value="eliminar">
        <input type="hidden" name="id" id="delete_id">

        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarRegistroHorarioLabel">Editar Registro Horario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <p>Indica el motivo por el que quieres editar el registro</p>
          <div id="mensaje_modal" class="text-success mt-2 d-none alert" role="alert"></div>
          <textarea id="motivo_edicion_input" class="form-control w-100" required></textarea>
        </div>

        <div class="modal-footer">
          <button id="botonConfirmarEditarRegistro" type="button" class="btn btn-success">Confirmar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script type="module" src="/public/js/reg_hor_list_events.js"></script>
<script type="module" src="/public/js/reg_hor_list_core.js"></script>
<script type="module" src="/public/js/reg_hor_list_descansos.js"></script>
<script>
    const dept_usuario = "<?= $_SESSION['dept'] ?>";
</script>

<script type="module" src="/public/js/buscador_usuarios.js"></script>
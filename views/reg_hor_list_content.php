<div class="mb-4">
  <h1>Listado de Registros Horarios</h1>
</div>

<div class="card p-4 mb-4">
<div class="row align-items-end">
  <div class="col-md-3 position-relative">
    <label for="usuario-buscador" class="form-label">Usuario</label>
    <input type="text" id="usuario-buscador" class="form-control" placeholder="Buscar usuario..." autocomplete="off">
    <input type="hidden" id="usuario">
    <div id="sugerencias-usuarios" class="list-group position-absolute w-100 z-3"></div>
  </div>

  <div class="col-md-3 mb-3">
    <label for="fecha_desde" class="form-label">Fecha desde</label>
    <input type="date" id="fecha_desde" class="form-control">
  </div>

  <div class="col-md-3 mb-3">
    <label for="fecha_hasta" class="form-label">Fecha hasta</label>
    <input type="date" id="fecha_hasta" class="form-control">
  </div>

  <div class="col-md-3 mb-3">
    <label for="cantidad" class="form-label">Registros por página</label>
    <select id="cantidad" class="form-select">
      <option value="10" selected>10</option>
      <option value="25">25</option>
      <option value="50">50</option>
      <option value="100">100</option>
    </select>
  </div>
</div>

<div class="d-flex justify-content-end mb-3">
  <button id="btn-filtrar" class="btn btn-primary me-2">
    <i class="bi bi-funnel"></i> Filtrar
  </button>
  <button id="btn-imprimir" class="btn btn-secondary">
    <i class="bi bi-printer"></i> Imprimir
  </button>
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

<!-- Scripts -->

<script type="module" src="/public/js/reg_hor_list_events.js"></script>
<script type="module" src="/public/js/reg_hor_list_core.js"></script>
<script type="module" src="/public/js/reg_hor_list_descansos.js"></script>

<script type="module" src="/public/js/buscador_usuarios.js"></script>
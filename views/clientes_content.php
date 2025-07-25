<div class="container-fluid mt-2">

  <!-- Alerta de feedback -->
  <div id="mensaje" class="alert alert-success d-none" role="alert"></div>

  <!-- Encabezado -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Listado de clientes</h2>
    <a href="/crear_cliente" class="btn btn-primary">
      <i class="bi bi-person-plus-fill"></i> Crear nuevo cliente
    </a>
  </div>

  <!-- Filtros -->
  <form class="row g-3 align-items-end mb-4" onsubmit="return false;">
    <div class="col-md-3">
      <label for="filtrar_nombre" class="form-label">Nombre</label>
      <input type="text" id="filtrar_nombre" class="form-control">
      <div id="sugerencias-nombre" class="list-group"></div>
    </div>

    <div class="col-md-1">
      <label for="filtrar_id" class="form-label">ID</label>
      <input type="text" id="filtrar_id" class="form-control">
    </div>

    <div class="col-md-2">
      <label for="filtrar_telefono" class="form-label">Teléfono</label>
      <input type="text" id="filtrar_telefono" class="form-control">
    </div>

    <div class="col-md-2">
      <label for="filtrar_dni" class="form-label">DNI</label>
      <input type="text" id="filtrar_dni" class="form-control">
    </div>

    <div class="col-md-2">
      <label for="cantidad" class="form-label">Registros por página</label>
      <select id="cantidad" class="form-select">
        <option value="10" selected>10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
    </div>

    <div class="col-md-2 d-grid gap-2">
      <button id="btn-filtrar" class="btn btn-primary">
        <i class="bi bi-funnel"></i> Filtrar
      </button>
      <button id="btn-imprimir" class="btn btn-secondary">
        <i class="bi bi-printer"></i> Imprimir
      </button>
    </div>
  </form>

  <!-- Tabla de resultados -->
  <div class="table-responsive">
    <table id="tabla_clientes" class="table table-hover align-middle small">
      <thead class="table-dark">
        <tr>
          <th>Nombre</th>
          <th>ID</th>
          <th>Teléfono</th>
          <th>DNI</th>
          <th>Dirección</th>
          <th>Ciudad</th>
          <th>CP</th>
          <th>Provincia</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($clientes as $cliente): ?>
          <tr>
            <td><?= htmlspecialchars($cliente['CNOM']) ?></td>
            <td><?= htmlspecialchars($cliente['CCODCL']) ?></td>
            <td><?= htmlspecialchars($cliente['CTEL1']) ?></td>
            <td><?= htmlspecialchars($cliente['CDNI']) ?></td>
            <td><?= htmlspecialchars($cliente['CDOM']) ?></td>
            <td><?= htmlspecialchars($cliente['CPOB']) ?></td>
            <td><?= htmlspecialchars($cliente['CCODPO']) ?></td>
            <td><?= htmlspecialchars($cliente['CPAIS']) ?></td>
            <td class="text-center">
              <button type="button" class="btn btn-sm btn-primary btn-editar"
                title="Editar"
                data-cliente='<?= json_encode($cliente, JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>'>
                <i class="bi bi-pencil-square"></i>
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Paginación -->
  <div id="paginacion" class="mt-3"></div>

  <!-- Modal edición -->
  <?php include_once __DIR__ . '/components/modal_clientes/modal_editar_cliente.php'; ?>
</div>

<!-- Scripts JS -->
<script src="/public/js/clientes/clientes.js"></script>
<script src="/public/js/clientes/clientes_editar.js"></script>

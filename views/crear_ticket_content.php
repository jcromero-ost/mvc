<div class="container mt-4">
  <h2 class="mb-4">Crear nuevo ticket</h2>
  <?php include_once __DIR__ . '/components/alerts.php'; ?>
  <form method="POST" action="/store_ticket" enctype="multipart/form-data">
    <div class="row mb-3">
      <div class="col-md-6">
        <label for="nombre" class="form-label">Cliente</label>
        <div class="d-flex align-items-center">
          <input type="text" class="form-control me-2" id="cliente" name="cliente" required readonly>
          <button class="btn btn-outline-secondary btn-lupa" type="button" id="botonBuscarCliente">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </div>
      <div class="col-md-6">
        <label for="medio_comunicacion" class="form-label">Medio de comunicación</label>
        <select id="medio_comunicacion" name="medio_comunicacion" class="form-select" required>
            <option value="">Seleccione un medio</option>
            <?php foreach ($medios_comunicacion as $medio): ?>
              <option value="<?= $medio['id'] ?>"><?= htmlspecialchars($medio['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="tecnico" class="form-label">Técnico</label>
        <select id="tecnico" name="tecnico" class="form-select">
            <option value="">Seleccione un tecnico</option>
            <option value="">Pendiente de asignar</option>
            <?php foreach ($tecnicos as $tec): ?>
              <option value="<?= $tec['id'] ?>"><?= htmlspecialchars($tec['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label for="fecha_inicio" class="form-label">Fecha Inicio Ticket</label>
        <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo date('Y-m-d\TH:i'); ?>" readonly>      
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-12">
        <label for="descripcion" class="form-label">Descripción</label>
        <div class="input-group">
          <textarea class="form-control" id="descripcion" name="descripcion" rows="10" required></textarea>
        </div>
      </div>
    </div>

    <button type="submit" name="accion" value="crear" class="btn btn-primary">Guardar ticket</button>
  </form>
</div>

<!-- Modal Cliente -->
<?php include_once __DIR__ . '/components/modal_cliente_ticket.php'; ?>

<script src="/public/js/tickets/tickets_crear.js" defer></script>

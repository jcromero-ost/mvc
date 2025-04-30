<div class="container mt-4">
  <h2 class="mb-4">Crear nuevo ticket</h2>
  <?php include_once __DIR__ . '/components/alerts.php'; ?>
  <form method="POST" action="/store_usuario" enctype="multipart/form-data">
    <div class="row mb-3">
      <div class="col-md-6">
        <label for="nombre" class="form-label">Cliente</label>
        <input type="text" class="form-control" id="cliente" name="cliente" required>
      </div>
      <div class="col-md-6">
        <label for="alias" class="form-label">Medio de comunicación</label>
        <input type="text" class="form-control" id="incidencia" name="incidencia">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="email" class="form-label">Técnico</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="col-md-6">
        <label for="telefono" class="form-label">Fecha Inicio Ticket</label>
        <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio">
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

    <button type="submit" name="accion" value="crear" class="btn btn-primary">Guardar usuario</button>
  </form>
</div>

<script src="/public/js/crear_usuario.js" defer></script>
<script src="/public/js/cropper_util.js"></script>

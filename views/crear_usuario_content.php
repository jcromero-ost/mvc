<div class="container mt-4">
  <h2 class="mb-4">Crear nuevo usuario</h2>
  <?php include_once __DIR__ . '/components/alerts.php'; ?>
  <form method="POST" action="/store_usuario" enctype="multipart/form-data">
    <div class="row mb-3">
      <div class="col-md-6">
        <label for="nombre" class="form-label">Nombre completo</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
      </div>
      <div class="col-md-6">
        <label for="alias" class="form-label">Alias / Usuario</label>
        <input type="text" class="form-control" id="alias" name="alias">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="col-md-6">
        <label for="telefono" class="form-label">Teléfono / Extensión</label>
        <input type="text" class="form-control" id="telefono" name="telefono">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="fecha_ingreso" class="form-label">Fecha de ingreso</label>
        <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso">
      </div>
      <div class="col-md-6">
        <label for="departamento_id" class="form-label">Departamento</label>
        <select name="departamento_id" id="departamento_id" class="form-select" required>
          <option value="">Seleccionar...</option>
          <?php foreach ($departamentos as $dep): ?>
            <option value="<?= $dep['id'] ?>"><?= htmlspecialchars($dep['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="mb-3">
      <label for="foto" class="form-label">Foto de perfil (drag & drop o clic)</label>
      <div id="drop-area" class="border border-secondary rounded p-4 text-center" style="background-color:#f8f9fa; cursor:pointer;">
        <p id="drop-text">Arrastra la imagen aquí o haz clic para seleccionar</p>
        <input type="file" id="foto" class="form-control d-none" accept="image/*">
        <div id="preview-container" class="mt-3 d-none">
          <img id="preview" class="img-thumbnail mb-2" style="max-height:300px;">
          <hr>
          <div class="text-end">
            <button type="button" id="btn-clear" class="btn btn-sm btn-outline-danger">Quitar imagen</button>
          </div>
        </div>
      </div>
      <input type="hidden" name="foto_recortada" id="foto_recortada">
    </div>

    <div class="row mb-3">
  <div class="col-md-6">
    <label for="password" class="form-label">Contraseña</label>
    <div class="input-group">
      <input type="password" class="form-control" id="password" name="password" required>
      <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Mostrar contraseña">
  <i class="bi bi-eye-slash" id="iconPassword"></i>
</button>
    </div>
  </div>
  <div class="col-md-6">
    <label for="confirm_password" class="form-label">Repetir contraseña</label>
    <div class="input-group">
      <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
      <button class="btn btn-outline-secondary" type="button" id="toggleConfirm">
        <i class="bi bi-eye-slash" id="iconConfirm"></i>
      </button>
    </div>
    <div id="passwordHelp" class="form-text text-danger d-none">Las contraseñas no coinciden.</div>
  </div>
</div>


<div class="mb-4">
      <label class="form-label d-block mb-2">Estado</label>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="activo" id="activo1" value="1" checked>
        <label class="form-check-label" for="activo1">Activo</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="activo" id="activo0" value="0">
        <label class="form-check-label" for="activo0">Inactivo</label>
      </div>
    </div>

    <button type="submit" name="accion" value="crear" class="btn btn-primary">Guardar usuario</button>
  </form>
</div>

<script src="/public/js/crear_usuario.js" defer></script>

<div class="container mt-4">
  <h2 class="mb-4">Crear nuevo usuario</h2>

  <form method="POST" action="/../controllers/UsuarioController.php" enctype="multipart/form-data">
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
      <div>
        <button type="button" id="btn-clear" class="btn btn-sm btn-outline-danger">Quitar imagen</button>
      </div>
    </div>
  </div>
  <input type="hidden" name="foto_recortada" id="foto_recortada">
</div>



    <div class="row mb-3">
      <div class="col-md-6">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <div class="col-md-6">
        <label for="confirm_password" class="form-label">Repetir contraseña</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
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

<script>
  const password = document.getElementById('password');
  const confirm = document.getElementById('confirm_password');
  const help = document.getElementById('passwordHelp');

  confirm.addEventListener('input', () => {
    help.classList.toggle('d-none', confirm.value === password.value);
  });

  let cropper;
const dropArea = document.getElementById('drop-area');
const fileInput = document.getElementById('foto');
const preview = document.getElementById('preview');
const previewContainer = document.getElementById('preview-container');
const hiddenField = document.getElementById('foto_recortada');

// Activar cropper y mostrar imagen
function handleFile(file) {
  const url = URL.createObjectURL(file);
  preview.src = url;
  preview.classList.remove('d-none');
  previewContainer.classList.remove('d-none');

  if (cropper) cropper.destroy();

  cropper = new Cropper(preview, {
    aspectRatio: 1,
    viewMode: 1,
    cropend: function () {
      const canvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300
      });
      hiddenField.value = canvas.toDataURL('image/jpeg');
    }
  });
}

// Click para abrir el input
dropArea.addEventListener('click', (e) => {
  // Solo activa el input si el click fue directamente en el contenedor, no en sus hijos
  if (e.target === dropArea) {
    fileInput.click();
  }
});


// Cuando se selecciona desde el input
fileInput.addEventListener('change', e => {
  const file = e.target.files[0];
  if (file) handleFile(file);
});

// Drag & drop
dropArea.addEventListener('dragover', e => {
  e.preventDefault();
  dropArea.classList.add('bg-light');
});
dropArea.addEventListener('dragleave', () => {
  dropArea.classList.remove('bg-light');
});
dropArea.addEventListener('drop', e => {
  e.preventDefault();
  dropArea.classList.remove('bg-light');
  const file = e.dataTransfer.files[0];
  if (file) handleFile(file);
});
clearBtn.addEventListener('click', () => {
  fileInput.value = "";
  previewContainer.classList.add('d-none');
  preview.src = "";
  dropText.classList.remove('d-none');
  hiddenField.value = "";
  if (cropper) {
    cropper.destroy();
    cropper = null;
  }
});

</script>

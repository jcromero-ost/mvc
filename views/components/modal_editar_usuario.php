<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="formEditarUsuario" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="accion" value="editar">
          <input type="hidden" name="id" id="edit_id">
          <input type="hidden" name="foto_recortada" id="edit_foto_recortada">

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="edit_nombre" class="form-label">Nombre completo</label>
              <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
            </div>
            <div class="col-md-6">
              <label for="edit_alias" class="form-label">Alias</label>
              <input type="text" class="form-control" id="edit_alias" name="alias">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="edit_email" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" id="edit_email" name="email" required>
            </div>
            <div class="col-md-6">
              <label for="edit_telefono" class="form-label">Teléfono / Extensión</label>
              <input type="text" class="form-control" id="edit_telefono" name="telefono">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="edit_fecha_ingreso" class="form-label">Fecha de ingreso</label>
              <input type="date" class="form-control" id="edit_fecha_ingreso" name="fecha_ingreso">
            </div>
            <div class="col-md-6">
              <label for="edit_departamento_id" class="form-label">Departamento</label>
              <select name="departamento_id" id="edit_departamento_id" class="form-select" required>
                <option value="">Seleccionar...</option>
                <?php foreach ($departamentos as $dep): ?>
                  <option value="<?= $dep['id'] ?>"><?= htmlspecialchars($dep['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- Foto -->
          <div class="mb-3">
            <label class="form-label">Foto de perfil</label>
            <div id="edit-drop-area" class="border border-secondary rounded p-4 text-center" style="background-color:#f8f9fa; cursor:pointer;">
              <p id="edit-drop-text">Arrastra la imagen aquí o haz clic para seleccionar</p>
              <input type="file" id="edit_foto" class="form-control d-none" accept="image/*">
              <div id="edit-preview-container" class="mt-3 d-none">
                
                <img id="edit_preview" class="img-thumbnail mb-2" style="max-height:300px;">
                <div class="text-end mt-2">
                  <button type="button" id="edit-btn-crop" class="btn btn-sm btn-outline-primary">Aplicar recorte</button>
                  <button type="button" id="edit-btn-clear" class="btn btn-sm btn-outline-danger">Quitar imagen</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Estado -->
          <div class="mb-3">
            <label class="form-label d-block mb-2">Estado</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="activo" id="edit_activo1" value="1">
              <label class="form-check-label" for="edit_activo1">Activo</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="activo" id="edit_activo0" value="0">
              <label class="form-check-label" for="edit_activo0">Inactivo</label>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar cambios</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

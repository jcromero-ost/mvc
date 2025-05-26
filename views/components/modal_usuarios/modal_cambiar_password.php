<div class="modal fade" id="modalCambiarPassword" tabindex="-1" aria-labelledby="modalCambiarPasswordLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="formCambiarPassword" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCambiarPasswordLabel">Cambiar Contraseña</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="accion" value="cambiar_passwd">
          <input type="hidden" name="id" id="cambiar_id">

          <div class="row mb-3">
            <div class="col-md-12">
              <label for="password" class="form-label">Contraseña</label>
              <input type="text" class="form-control" id="password" name="password" required>
            </div>
          </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Cambiar contraseña</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

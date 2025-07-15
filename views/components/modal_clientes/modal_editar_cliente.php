<!-- Modal edición de cliente -->
<div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalEditarClienteLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarClienteLabel">Editar Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form id="form_editar_cliente" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" id="edit_id">

          <!-- Datos personales -->
          <div class="d-flex align-items-center">
            <i class="bi bi-person me-2"></i>
            <h6 class="mb-0">Datos personales</h6>
          </div>
          <hr>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="nombre" class="form-label">Nombre completo</label>
              <input type="text" class="form-control text-uppercase" id="nombre" name="nombre" required>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control text-uppercase" id="apellidos" name="apellidos" required>
            </div>
        </div>

          <div class="row mb-3">
            <div class="col-md-3">
              <label for="telefono" class="form-label">Teléfono</label>
              <input type="text" class="form-control" id="telefono" name="telefono" maxlength="9" required>
            </div>
            <div class="col-md-3">
              <label for="dni" class="form-label">DNI</label>
              <div class="input-group mb-2">
                <input type="text" class="form-control" id="dni" name="dni" maxlength="9" required>
                <button type="button" class="btn btn-outline-secondary" id="validarDNI">Validar DNI</button>
              </div>
              <small id="dniMensaje" class="form-text"></small>
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
          </div>

          <!-- Dirección -->
          <div class="d-flex align-items-center mt-4">
            <i class="bi bi-house me-2"></i>
            <h6 class="mb-0">Datos de dirección</h6>
          </div>
          <hr>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="direccion" class="form-label">Dirección</label>
              <input type="text" class="form-control text-uppercase" id="direccion" name="direccion">
            </div>
            <div class="col-md-6">
              <label for="ciudad" class="form-label">Ciudad</label>
              <input type="text" class="form-control text-uppercase" id="ciudad" name="ciudad" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="cp" class="form-label">Código Postal</label>
              <input type="number" class="form-control" id="cp" name="cp">
            </div>
            <div class="col-md-6">
              <label for="provincia" class="form-label">Provincia</label>
              <input list="provincias" type="text" class="form-control text-uppercase" id="provincia" name="provincia" required>
              <datalist id="provincias">
                <option value="ALMERIA">
                <option value="CADIZ">
                <option value="CORDOBA">
                <option value="GRANADA">
                <option value="HUELVA">
                <option value="JAEN">
                <option value="MALAGA">
                <option value="SEVILLA">
              </datalist>
            </div>
          </div>

          <div class="text-end">
            <button id="botonEditarCliente" type="submit" class="btn btn-primary">
              <i class="bi bi-save"></i> Guardar cambios
            </button>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>

    </div>
  </div>
</div>

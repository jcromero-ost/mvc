<div class="modal fade" id="modalCrearCliente" tabindex="-1" aria-labelledby="modalCrearClienteLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <form id="formCrearCliente" method="POST"  action="/store_cliente_ticket" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCrearClienteLabel">Crear cliente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
            <div class="d-flex">
                <i class="bi bi-person me-3"></i>
                <p class="mb-0">Datos personales</p>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono">
                </div>
                <div class="col-md-3">
                    <label for="dni" class="form-label">DNI</label>
                    <input type="text" class="form-control" id="dni" name="dni">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>

            <div class="d-flex mt-5">
                <i class="bi bi-house me-3"></i>
                <p class="mb-0">Datos de dirección</p>
            </div>
            <hr>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="direccion" class="form-label">Direccion</label>
                    <input type="text" class="form-control" id="direccion" name="direccion">
                </div>
                <div class="col-md-6">
                    <label for="ciudad" class="form-label">Ciudad</label>
                    <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="cp" class="form-label">Código Postal</label>
                    <input type="text" class="form-control" id="cp" name="cp">
                </div>
                <div class="col-md-6">
                    <label for="ciudad" class="form-label">Provincia</label>
                    <input list="provincias" type="text" class="form-control" id="ciudad" name="ciudad" required>
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
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success btn-primary">Guardar cambios</button>
          <button type="button" class="btn btn-secondary btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="/public/js/tickets/tickets_modal_cliente_crear.js"></script>

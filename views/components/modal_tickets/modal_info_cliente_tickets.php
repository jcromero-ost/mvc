<div class="modal fade" id="modalInfoCliente" tabindex="-1" aria-labelledby="modalInfoClienteLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="formCrearCliente" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="modalInfoClienteLabel">Datos del cliente</h5>
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
                    <input type="text" class="form-control text-uppercase" id="nombre" name="nombre" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="telefono" class="form-label">Teléfono</label>
				<input type="text" class="form-control" id="telefono" name="telefono" maxlength="9" readonly>
                </div>
                <div class="col-md-3">
                    <label for="dni" class="form-label">DNI</label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="dni" name="dni" maxlength="9" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" readonly>
                </div>
            </div>

            <div class="d-flex mt-5">
                <i class="bi bi-house me-3"></i>
                <p class="mb-0">Datos de dirección</p>
            </div>
            <hr>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="direccion" class="form-label text-uppercase">Direccion</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" readonly>
                </div>
                <div class="col-md-6">
                    <label for="ciudad" class="form-label text-uppercase">Ciudad</label>
                    <input type="text" class="form-control" id="ciudad" name="ciudad" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="cp" class="form-label">Código Postal</label>
                    <input type="text" class="form-control" id="cp" name="cp" readonly>
                </div>
                <div class="col-md-6">
                    <label for="ciudad" class="form-label text-uppercase">Provincia</label>
                    <input list="provincias" type="text" class="form-control" id="provincia" name="provincia" readonly>
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
          <button type="button" class="btn btn-secondary btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="/public/js/tickets/tickets_modal_cliente_info.js"></script>

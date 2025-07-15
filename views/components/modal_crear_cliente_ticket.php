<div class="modal fade" id="modalCrearCliente" tabindex="-1" aria-labelledby="modalCrearClienteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalCrearClienteLabel">Crear nuevo cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form method="POST" action="/store_cliente" id="formCrearCliente" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre completo</label>
            <input type="text" class="form-control text-uppercase" id="nombre" name="nombre" required>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="telefono" class="form-label">Teléfono</label>
              <input type="text" class="form-control" id="telefono" name="telefono" maxlength="9" required>
            </div>
            <div class="col-md-6">
              <label for="dni" class="form-label">DNI</label>
              <input type="text" class="form-control" id="dni" name="dni" maxlength="9" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>

          <hr>

          <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control text-uppercase" id="direccion" name="direccion">
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="ciudad" class="form-label">Ciudad</label>
              <input type="text" class="form-control text-uppercase" id="ciudad" name="ciudad" required>
            </div>
            <div class="col-md-6">
              <label for="cp" class="form-label">Código Postal</label>
              <input type="text" class="form-control" id="cp" name="cp">
            </div>
          </div>

          <div class="mb-3">
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
        </form>
      </div>

      <div class="modal-footer">
        <button type="submit" form="formCrearCliente" class="btn btn-primary">Guardar cliente</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>

    </div>
  </div>
</div>

<script src="/public/js/clientes/clientes_crear.js" defer></script>

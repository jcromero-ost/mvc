<div class="container mt-5">
  <h2 class="text-center mb-4"><i class="bi bi-file-plus me-2"></i>Crear contrato para cliente</h2>

  <!-- Buscador de cliente -->
  <div class="mb-4">
    <label for="cliente_search" class="form-label">Buscar cliente</label>
    <input type="text" class="form-control" id="cliente_search" placeholder="Nombre, teléfono o DNI" autocomplete="off">
    <input type="hidden" id="cliente_id" name="cliente_id">
    <div id="cliente_suggestions" class="list-group mt-1 position-absolute" style="z-index: 1000;"></div>
  </div>

  <!-- Info del cliente seleccionado (inicialmente oculta) -->
  <div id="cliente_info" class="card mb-4 d-none">
    <div class="card-header bg-info text-white">
      <strong>Datos del cliente</strong>
    </div>
    <div class="card-body row" id="cliente_info_body">
      <!-- Contenido cargado dinámicamente por JS -->
    </div>
  </div>

  <!-- Formulario de contrato (oculto hasta seleccionar cliente) -->
  <form method="POST" action="/contratos/store" id="formContrato" class="d-none">
    <input type="hidden" name="cliente_id" id="form_cliente_id">

    <div class="row mb-4">
      <div class="col-md-6 mb-3">
        <label for="fecha_alta" class="form-label">Fecha de alta</label>
        <input type="date" class="form-control" id="fecha_alta" name="fecha_alta" required>
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label">Servicios contratados</label>
      <div class="row">
        <div class="col-md-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="servicio[]" value="servidores" id="serv_servidores">
            <label class="form-check-label" for="serv_servidores">Servidores</label>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="servicio[]" value="hosting" id="serv_hosting">
            <label class="form-check-label" for="serv_hosting">Hosting</label>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="servicio[]" value="mail" id="serv_mail">
            <label class="form-check-label" for="serv_mail">Correo electrónico</label>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="servicio[]" value="dominios" id="serv_dominios">
            <label class="form-check-label" for="serv_dominios">Dominios</label>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-success px-5 py-2">
        <i class="bi bi-check-circle me-2"></i>Guardar contrato
      </button>
    </div>
  </form>
</div>

<script src="/public/js/contratosCli/crear_contratoCli.js" defer></script>

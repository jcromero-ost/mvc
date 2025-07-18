<div class="container mt-4">
  <h2 class="mb-4">Crear nuevo cliente</h2>
  <?php include_once __DIR__ . '/components/alerts.php'; ?>
  <form method="POST" action="/store_cliente" enctype="multipart/form-data">
        <div class="d-flex">
            <i class="bi bi-person me-3"></i>
            <p class="mb-0">Datos personales</p>
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre</label>
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

        <div class="d-flex mt-5">
            <i class="bi bi-house me-3"></i>
            <p class="mb-0">Datos de dirección</p>
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

        <button type="submit" name="accion" value="crear" class="btn btn-primary">Guardar cliente</button>
    </form>
</div>

<script src="/public/js/clientes/clientes_crear.js" defer></script>

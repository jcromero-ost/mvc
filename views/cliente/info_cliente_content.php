<div class="card border-primary mb-3">
    <div class="card-body">

        <fieldset class="border p-3 rounded mb-3">
            <legend class="w-auto px-2 fs-6 text-primary"><i class="bi bi-person-circle me-2"></i>Datos personales</legend>

            <div class="row mb-2">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Nombre completo</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($cliente['nombre']) ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Teléfono</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($cliente['telefono']) ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Correo electrónico</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($cliente['email']) ?>" readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">DNI</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($cliente['dni']) ?>" readonly>
                </div>
            </div>
        </fieldset>

        <fieldset class="border p-3 rounded">
            <legend class="w-auto px-2 fs-6 text-primary"><i class="bi bi-geo-alt-fill me-2"></i>Datos de dirección</legend>

            <div class="row mb-2">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Dirección</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($cliente['direccion']) ?>" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Ciudad</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($cliente['ciudad']) ?>" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Provincia</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($cliente['provincia']) ?>" readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Código Postal</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($cliente['cp']) ?>" readonly>
                </div>
            </div>
        </fieldset>

    </div>
</div>

<?php
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

// IMPORTANTE: aquí cargamos los festivos antes de incluir la tabla
require_once __DIR__ . '/../models/Festivo.php';
$festivos = Festivo::allByYear($anio);
?>

<div class="mb-4">
<div id="alerta-festivo" class="alert d-none" role="alert"></div>

    <form id="form-agregar-festivo" class="row g-3">
        <div class="col-md-2">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" class="form-control" name="fecha" required>
        </div>
        <div class="col-md-2">
            <label for="tipo" class="form-label">Tipo</label>
            <select class="form-select" name="tipo" required>
                <option value="nacional">Nacional</option>
                <option value="autonomico">Autonómico</option>
                <option value="local">Local</option>
                <option value="empresa">Empresa</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <input type="text" class="form-control" name="descripcion" required>
        </div>
        <div class="col-md-2">
            <label for="comunidad_autonoma" class="form-label">Com. Autónoma</label>
            <input type="text" class="form-control" name="comunidad_autonoma">
        </div>
        <div class="col-md-2">
            <label for="municipio" class="form-label">Municipio</label>
            <input type="text" class="form-control" name="municipio">
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Agregar</button>
        </div>
    </form>
</div>

<div class="mb-3">
    <label for="filtro-anio" class="form-label">Filtrar por año</label>
    <select id="filtro-anio" class="form-select w-auto d-inline-block">
        <?php for ($i = date('Y') - 2; $i <= date('Y') + 2; $i++): ?>
            <option value="<?= $i ?>" <?= $i == $anio ? 'selected' : '' ?>><?= $i ?></option>
        <?php endfor; ?>
    </select>
</div>

<div id="tabla-festivos">
    <?php include 'components/tabla_festivos.php'; ?>
</div>

<script src="/public/js/festivos/festivos.js"></script>

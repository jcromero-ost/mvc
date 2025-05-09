<h1>Gestión de Contratos Laborales</h1>

<?php if (isset($_GET['ok']) && $_GET['ok'] == 1): ?>
    <div class="alert alert-success">Contrato laboral guardado correctamente.</div>
<?php endif; ?>


<form id="form-contrato" class="row g-3 mt-3" method="POST" action="/contratos_laborales/guardar">
    <!-- Usuario -->
    
    <div class="col-md-6 position-relative">
        <label for="usuario-buscador" class="form-label">Buscar usuario</label>
        <input type="search" id="usuario-buscador" class="form-control" placeholder="Nombre del usuario" autocomplete="off">
        <input type="hidden" name="usuario_id" id="usuario">
        <div id="sugerencias-usuarios" class="list-group position-absolute w-100 z-3" style="max-height: 200px; overflow-y: auto;"></div>
    </div>


    <!-- Tipo de contrato -->
    <div class="col-md-6">
        <label for="tipo" class="form-label">Tipo de Contrato</label>
        <select id="tipo" name="tipo" class="form-select" required>
            <option value="">Seleccione</option>
            <option value="completa">Jornada completa (8h)</option>
            <option value="media">Media jornada (4h)</option>
            <option value="parcial">Parcial (personalizado)</option>
        </select>
    </div>

    <!-- Horas diarias -->
    <div class="col-md-4">
        <label for="horas_diarias" class="form-label">Horas por día</label>
        <input type="number" step="0.25" min="0" max="12" class="form-control" id="horas_diarias" name="horas_diarias" required>
    </div>

    <!-- Fecha inicio -->
    <div class="col-md-4">
        <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
    </div>

    <!-- Fecha fin -->
    <div class="col-md-4">
        <label for="fecha_fin" class="form-label">Fecha de fin (opcional)</label>
        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
    </div>

    <!-- Observaciones -->
    <div class="col-12">
        <label for="observaciones" class="form-label">Observaciones</label>
        <textarea id="observaciones" name="observaciones" class="form-control" rows="2"></textarea>
    </div>

    <!-- Botón -->
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Guardar contrato</button>
    </div>
</form>


<!-- Script -->
<script src="/public/js/buscador_usuarios.js"></script>

<main class="container-fluid mt-2">
    <h2 class="mb-4">Solicitar Vacaciones</h2>

    <form id="formSolicitudVacaciones" method="POST" action="/store_vacaciones" enctype="multipart/form-data">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            </div>

            <div class="col-md-6">
                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="motivo" class="form-label">Motivo</label>
                <textarea id="motivo" name="motivo" class="form-control"></textarea>
            </div>
        </div>

        <button type="submit" name="accion" value="crear" class="btn btn-primary">Enviar Solicitud</button>
    </form>

    <h2 class="mt-4">Solicitudes</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Usuario</th>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Fecha Solicitud</th>
                    <th>Estado</th>
                    <th>Revisado por</th>
                    <th>Motivo de rechazo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitudes as $solicitud): ?>
                    <tr>
                        <td><?= htmlspecialchars($solicitud['usuario_nombre'] ?? '') ?></td>
                        <td><?= htmlspecialchars($solicitud['fecha_inicio'] ?? '') ?></td>
                        <td><?= htmlspecialchars($solicitud['fecha_fin'] ?? '') ?></td>
                        <td><?= htmlspecialchars($solicitud['fecha_creacion'] ?? '') ?></td>
                        <td><?= htmlspecialchars($solicitud['estado'] ?? '') ?></td>
                        <td><?= htmlspecialchars($solicitud['revisado_por_nombre'] ?? '') ?></td>
                        <td><?= htmlspecialchars($solicitud['rechazo_motivo'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>                  
            </tbody>
            <tfoot class="fw-bold table-light">
                
            </tfoot>
        </table>
    </div>




</main>
<script src="public/js/vacaciones/vacaciones.js"></script>


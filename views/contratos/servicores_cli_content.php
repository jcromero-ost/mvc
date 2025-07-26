<div class="container mt-4">
    <h2>Servidores del Contrato #<?= htmlspecialchars($_GET['contrato_id']) ?></h2>

    <form action="/servidores_cli/store" method="POST" class="mb-4">
        <input type="hidden" name="contrato_id" value="<?= htmlspecialchars($_GET['contrato_id']) ?>">
        
        <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        
        <div class="mb-2">
            <label class="form-label">IP</label>
            <input type="text" name="ip" class="form-control" required>
        </div>
        
        <div class="mb-2">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="2"></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Agregar Servidor</button>
    </form>

    <h5>Listado de servidores</h5>
    <ul class="list-group">
        <?php if (empty($servidores)): ?>
            <li class="list-group-item text-muted">No hay servidores registrados.</li>
        <?php else: ?>
            <?php foreach ($servidores as $srv): ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($srv['nombre']) ?></strong> (<?= htmlspecialchars($srv['ip']) ?>)
                    <br><small><?= htmlspecialchars($srv['descripcion']) ?></small>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

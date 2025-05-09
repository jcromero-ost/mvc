<table class="table table-striped">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Descripción</th>
            <th>Com. Autónoma</th>
            <th>Municipio</th>
            <th>Activo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($festivos as $f): ?>
        <tr>
            <td><?= htmlspecialchars($f['fecha']) ?></td>
            <td><?= ucfirst($f['tipo']) ?></td>
            <td><?= htmlspecialchars($f['descripcion']) ?></td>
            <td><?= htmlspecialchars($f['comunidad_autonoma']) ?></td>
            <td><?= htmlspecialchars($f['municipio']) ?></td>
            <td><?= $f['activo'] ? 'Sí' : 'No' ?></td>
            <td>
                <button class="btn btn-sm btn-danger btn-eliminar" data-id="<?= $f['id'] ?>">Eliminar</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

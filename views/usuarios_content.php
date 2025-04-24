<h2>Listado de usuarios</h2>
<a href="crear_usuario.php" class="btn btn-primary">Crear nuevo usuario</a>


<table class="table table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($usuarios)): ?>
    <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= htmlspecialchars($usuario['nombre']) ?></td>
            <td><?= htmlspecialchars($usuario['email']) ?></td>
        </tr>
    <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="2">No hay usuarios registrados.</td>
        </tr>
    <?php endif; ?>

    </tbody>
</table>

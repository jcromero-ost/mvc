<?php require_once '../session.php';?>
<?php include 'components/head.php'; ?>
<?php include 'components/header.php'; ?>
<?php include 'components/nav.php'; ?>


<h2>Listado de usuarios</h2>
<a href="index.php?r=crear_usuario">Crear nuevo usuario</a>
<table>
    <thead>
        <tr>
            <th>Nombre</th><th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                <td><?= htmlspecialchars($usuario['email']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'components/footer.php'; ?>

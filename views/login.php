<?php
session_start();
include 'components/alerts.php';
?>
<?php include 'components/alerts.php'; ?>
<form action="/auth.php" method="post">
    <input type="email" name="user" placeholder="Correo" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Iniciar sesión</button>
</form>

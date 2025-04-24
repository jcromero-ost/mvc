<?php
require_once 'session.php'; // Si necesitas verificar o inicializar sesión
session_unset();
session_destroy();

// Redirigir al login
header("Location: views/login.php");
exit;

<?php

if (session_status() === PHP_SESSION_NONE) 
    session_start(); 
// views/registro_horario.php

$view = 'registro_horario_content.php';
require_once 'layout.php';

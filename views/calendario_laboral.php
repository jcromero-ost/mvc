<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start(); 

// Aquí podrías cargar datos futuros si fueran necesarios

$view = './calendario_laboral_content.php';
include __DIR__ . '/layout.php';

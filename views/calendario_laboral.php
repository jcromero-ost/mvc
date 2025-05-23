<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start(); 

    require_once(__DIR__ . '/../models/unauth.php');

// Aquí podrías cargar datos futuros si fueran necesarios

$view = './calendario_laboral_content.php';
include __DIR__ . '/layout.php';

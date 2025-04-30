<?php 
// Captura el contenido que irá en el layout
ob_start(); 
require_once 'views/reg_hor_list_content.php';
$content = ob_get_clean();

// Carga el layout principal
require_once 'views/components/layout.php';
?>

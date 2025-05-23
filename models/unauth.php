<?php
    if (isset($_SESSION['dept']) && ($_SESSION['dept'] == 1)){
        header('Location: /unauthorized.php');
        exit;
    }
?>
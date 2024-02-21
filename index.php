<?php 
    require_once('databaseConnect.php');
    ob_start();
?>

<?php
    $titrePage = "Movies - Acceuil";

    $content = ob_get_clean();

    require_once "template.php"; 
?>
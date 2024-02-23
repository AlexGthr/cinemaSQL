<?php 
    require_once('databaseConnect.php');
    ob_start();
?>

<?php
    $titrePage = "Movies - Liste film";

    $content = ob_get_clean();

    require_once "template.php"; 
?>
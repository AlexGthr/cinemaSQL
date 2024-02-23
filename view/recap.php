<?php 
    require_once('databaseConnect.php');
    ob_start();
?>

<?php
    $titrePage = "Movies - Recap";

    $content = ob_get_clean();

    require_once "template.php"; 
?>
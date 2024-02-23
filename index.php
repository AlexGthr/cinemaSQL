<?php 
    require_once('databaseConnect.php');
    require_once('sql/requetesql.sql');
    ob_start();
?>

<p id="carousel">*********</br>ICI UN CAROUSSEL PLUS TARD DANS UN FUTUR PROCHE OU LOINTOIN</br>*********</p></div>

<h1 id="titleContent">A l'affiche !</h1>

<?php

 // On recupère le recipes ma requete sql
$afficheTitreFilm = $recipesAfficheEtTitreFilm->fetchAll();

 // Et je l'affiche ici
foreach($afficheTitreFilm as $afficheTitre) {

    if ($afficheTitre['affiche'] == "") {
        echo "";
    }
    
    else {
    echo "<img src='" . $afficheTitre['affiche'] . "'><br>",
            "<p id='teste'>" . $afficheTitre['titre'] . "</p>";
}}

?>

<?php
    $titrePage = "Movies - Acceuil";

    $content = ob_get_clean();

    require_once "template.php"; 
?>
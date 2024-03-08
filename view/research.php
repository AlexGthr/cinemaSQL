<?php 
    ob_start();
?>

<h1 id="titleSection" class="SectionTitle">Recherche :</h1>


<?php


$films = $requeteFilm->fetchAll();

if (!empty($films)) {

    echo "<h2> Film : </h2>";

    foreach ($films as $rechercheFilm) {
        ?>
        <li><a href="index.php?action=detFilm&id=<?= $rechercheFilm['id_film'] ?>"><?= $rechercheFilm['titre'] ?></a></li>
        <?php
    }
} ?>


<?php

$personnes = $requetePersonne->fetchAll();

if (!empty($personnes)) {

    echo "<h2> Personne : </h2>";

    foreach ($personnes as $recherchePersonne) {
        ?>
        <li><a href="index.php?action=detPersonne&id=<?= $recherchePersonne['id_personne'] ?>"><?= $recherchePersonne['nomPersonne'] ?></a></li>
        <?php
    }
} ?>

<?php

$roles = $requeteRole->fetchAll();

if (!empty($roles)) {

    echo "<h2> Role : </h2>";


    foreach ($roles as $rechercheRole) {
        ?>
        <li><a href="index.php?action=detRole&id=<?= $rechercheRole['id_role'] ?>"><?= $rechercheRole['nom'] ?></a></li>
        <?php
    }
} ?>

<?php

$categories = $requeteCategorie->fetchAll();

if (!empty($categories)) {

    echo "<h2> Catégorie : </h2>";


    foreach ($categories as $rechercheCategorie) {
        ?>
        <li><a href="index.php?action=detCategorie&id=<?= $rechercheCategorie['id_categorie'] ?>"><?= $rechercheCategorie['type'] ?></a></li>
        <?php
    }
} ?>

<?php if(empty($films) && empty($roles) && empty($categories) && empty($personnes)) { ?>

<div class="aucunResultat">
    <div class="textAucunResultat">
        <h2> 404 <span> NOT </span> FOUND </h2>
        <h3> Aucun résultats trouvée </h3>
        <a href="index.php" title="index"> Revenir en lieu sûr </a>
    </div>

    <figure class="notFoundresearch"> 
        <img id="notFoundImage" src="#" title="research not found">
    </figure>
    
    
</div>

<?php } ?>



<?php

    $titrePage = "Movies - Acceuil";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php"; 
?>
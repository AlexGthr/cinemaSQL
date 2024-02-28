<?php 
    ob_start();
?>

    <p>**********</br>ICI UN CAROUSSEL DANS LE FUTUR</br>**********</p>

    <h1 id="titleSection" class="SectionTitle">A l'affiche !</h1>

<div class="afficherTitreFilm">

        <!-- Affiche les affiches d'un film et le titre -->
    <?php 
        foreach($requete->fetchAll() as $film) { ?>
        <div class="film">
            <img class="afficheFilm" src='<?= $film["affiche"] ?>' title='<?= $film["titre"] ?>'>
            <a href="index.php?action=detFilm&id=<?= $film["id_film"] ?>"><?= $film["titre"] ?></a>
        </div>
        <?php } ?>

</div>

    <a href='index.php?action=listFilm' class="viewAll">view all</a>

    <h1 id="titleSection" class="SectionTitle">Acteur star !</h1>

<div class="afficherActeurs">

        <!-- Affiche la photo d'un acteur et son nom/prenom -->
    <?php 
        foreach($requeteA->fetchAll() as $acteur) { ?>
        <div class="acceuil">
            <img class="afficheActeur" src='<?= $acteur["photo"] ?>' title='<?= $acteur["acteur"] ?>'>
            <a href='index.php?action=detPersonne&id=<?= $acteur['id_personne']?>'><?= $acteur["acteur"] ?></a></br>
        </div>
        <?php } ?>

</div>

    <a href='index.php?action=listActeur' class="viewAll">view all</a>

    <h1 id="titleSection">Réalisateur du moment !</h1>

<div class="afficherRealisateurs">

            <!-- Affiche la photo d'un réalisateur et son nom/prenom -->
    <?php 
        foreach($requeteR->fetchAll() as $realisateur) { ?>
        <div class="acceuil">
            <img class="afficheReal" src='<?= $realisateur["photo"] ?>' title='<?= $realisateur["realisateur"] ?>'>
            <a href='index.php?action=detPersonne&id=<?= $realisateur['id_personne']?>'><?= $realisateur["realisateur"] ?></a></br>
        </div>
        <?php } ?>

</div>
        <a href='index.php?action=listReal' class="viewAll">view all</a>

<?php

    $titrePage = "Movies - Acceuil";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php"; 
?>
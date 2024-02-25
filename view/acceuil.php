<?php 
    ob_start();
?>

<p>**********</br>ICI UN CAROUSSEL DANS LE FUTUR</br>**********</p>

<h1 id="titleSection">A l'affiche !</h1>

<div class="afficherTitreFilm">

    <?php 
        foreach($requete->fetchAll() as $film) { ?>
        <div class="acceuil">
            <img class="afficheFilm" src='<?= $film["affiche"] ?>' title='<?= $film["titre"] ?>'>
            <a href="index.php?action=detFilm&id=<?= $film["id_film"] ?>"><?= $film["titre"] ?></a>
        </div>
        <?php } ?>

</div>

<h1 id="titleSection">Acteur star !</h1>

<div class="afficherActeurs">

    <?php 
        foreach($requeteA->fetchAll() as $acteur) { ?>
        <div class="acceuil">
            <img class="afficheActeur" src='<?= $acteur["photo"] ?>' title='<?= $acteur["acteur"] ?>'>
            <a href='index.php?action=detPersonne&id=<?= $acteur['id_personne']?>'><?= $acteur["acteur"] ?></a></br>
        </div>
        <?php } ?>

</div>

<h1 id="titleSection">Réalisateur du moment !</h1>

<div class="afficherRealisateurs">

    <?php 
        foreach($requeteR->fetchAll() as $realisateur) { ?>
        <div class="acceuil">
            <img class="afficheReal" src='<?= $realisateur["photo"] ?>' title='<?= $realisateur["realisateur"] ?>'>
            <?= $realisateur["realisateur"] ?></br>
        </div>
        <?php } ?>

</div>

<?php

    $titrePage = "Movies - Acceuil";
    $content = ob_get_clean();

    require_once "view/template.php"; 
?>
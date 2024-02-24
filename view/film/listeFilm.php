<?php 
    ob_start();
?>

    <label for="orderby">Trier par :</label>

        <select name="order" id="orderby">
            <option value="">Catégorie</option>
            <option value="date">Date de sortie</option>
            <option value="duree">Durée</option>
            <option value="note">Note</option>
        </select>

<div class="listFilms">

    <?php 
        foreach($requete->fetchAll() as $film) { ?>

        <div class="acceuil">

            <img class="afficheFilm" src='<?= $film["affiche"] ?>' title='<?= $film["titre"] ?>'>
            <p>« <?= $film["titre"] ?> »</p>
            <p>Date de sortie : <?= $film["dateDeSortie"] ?></p>
            <p>Durée : <?= $film["dureeFilm"] ?></p>
            <p>Note : <?= $film["note"] ?></p>

        </div>

        <?php } ?>

</div>

<?php
    $titrePage = "Movies - Films";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
<?php 
    ob_start();
?>
        <!-- Liste déroulante pour faire un trie d'affichage -->
    <label for="orderby">Trier par :</label>

        <select name="order" id="orderby">
            <option value="">Catégorie</option>
            <option value="date">Date de sortie</option>
            <option value="duree">Durée</option>
            <option value="note">Note</option>
        </select>

<div class="listFilms">


        <!-- Affiche la description d'un film, affiche/titre/date de sortie/durée/note -->
    <?php 
        foreach($requete->fetchAll() as $film) { ?>

        <div class="acceuil">

            <img class="afficheFilm" src='<?= $film["affiche"] ?>' title='<?= $film["titre"] ?>'>
            <a href="index.php?action=detFilm&id=<?= $film["id_film"] ?>">
                <p>« <?= $film["titre"] ?> »</p>
            </a>
            <p>Date de sortie : <?= $film["dateDeSortie"] ?></p>
            <p>Durée : <?= $film["dureeFilm"] ?></p>
            <p>Note : <?= $film["note"] ?></p>
            <p>Catégorie : <?= $film['categories'] ?> </p>

        </div>

        <?php } ?>

</div>

<?php
    $titrePage = "Movies - Films";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
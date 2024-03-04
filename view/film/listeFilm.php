<?php 
    ob_start();
?>
        <!-- Liste déroulante pour faire un trie d'affichage -->
    <label for="orderby">Trier par :</label>

        <select name="order" id="orderby">
            <option value="date">Date de sortie</option>
            <option value="duree">Durée</option>
            <option value="note">Note</option>
        </select>

        <h1 id="titleSection" class="SectionTitle">Nos films</h1>

<div class="listFilms">


        <!-- Affiche la description d'un film, affiche/titre/date de sortie/durée/note -->
    <?php 
        foreach($requete->fetchAll() as $film) { ?>

        <div class="acceuilFilm">

            <a href="index.php?action=detFilm&id=<?= $film["id_film"] ?>">
            <img src='<?= $film["affiche"] ?>' class="afficheFilm" title='<?= $film["titre"] ?>'>
                <p id="titleFilm"><?= $film["titre"] ?></p>
            </a>
            <p><span id="note"> <?= $film["note"] ?></span></p>
            <p><span>Durée :</span> <?= $film["dureeFilm"] ?></p>
            <p><span>Date de sortie :</span> <?= $film["dateDeSortie"] ?></p>

        </div>

        <?php } ?>

</div>

<?php
    $titrePage = "Movies - Films";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
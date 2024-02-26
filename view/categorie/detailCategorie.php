<?php 
    ob_start();
?>


<div class="listCategorie">

        <!-- Affiche la liste des categories -->
    <?php 

        $detailCategorie = $requete->fetch();

        echo "<h2> Catégorie « " . $detailCategorie['typeCategorie'] . " »</h2>",
                "<ul>";
    ?>

    <?php

        foreach($requeteFilmCategorie->fetchAll() as $categorie) { ?>

            <li>
                <a href="index.php?action=detFilm&id=<?= $categorie['idFilm'] ?>">
                <?= $categorie['titre'] ?></a> - <?= $categorie['dateDeSortie'] ?>
            </li>

        <?php } ?>

</div>

<?php
    $titrePage = "Movies - Catégorie";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
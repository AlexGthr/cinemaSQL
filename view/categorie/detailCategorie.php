<?php 
    ob_start();
?>


<div class="detailCategorie">

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
                <?= $categorie['titre'] ?></a> <?= $categorie['dateDeSortie'] ?>
            </li>

            <hr class="solid">

        <?php } ?>

</div>

<?php
    $titrePage = "Movies - Catégorie";
    $backLastPage = "<a href='index.php?action=listCategorie'>Revenir aux catégories</a>";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
<?php 
    ob_start();
?>

<h1 id="titleSection" class="SectionTitle">Nos catégories</h1>


<div class="listCategorie">

        <!-- Affiche la liste des categories -->
    <?php 

        echo "<h2> Catégorie de film </h2>",
                "<ul>";

                // requête pour l'affichage de la liste
        foreach($requete->fetchAll() as $categorie) { ?>

            <li>
                <a href="index.php?action=detCategorie&id=<?= $categorie['id_categorie'] ?>">
                <?= $categorie['type'] ?></a>
            </li>

            <hr class="solid">

        <?php } ?>

</div>

<?php
    $titrePage = "Movies - Catégorie";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
<?php 
    ob_start();
?>


<div class="listCategorie">

        <!-- Affiche la liste des categories -->
    <?php 

        echo "<h2> Catégorie de film : </h2>",
                "<ul>";

        foreach($requete->fetchAll() as $categorie) { ?>

            <li>
                <a href="index.php?action=detCategorie&id=<?= $categorie['id_categorie'] ?>">
                <?= $categorie['type'] ?></a>
            </li>

        <?php } ?>

</div>

<?php
    $titrePage = "Movies - Catégorie";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
<?php 
    ob_start();
?>


<div class="detailCategorie">

        <!-- Affiche la liste des categories -->
    <?php 

            // Requete pour afficher le nom de la catégorie
        $detailCategorie = $requete->fetch();

        echo "<h2> Catégorie « " . $detailCategorie['typeCategorie'] . " »</h2>",
                "<ul>";
    ?>

    <?php

            // Requete pour afficher le titre du film et sa date de sortie
        foreach($requeteFilmCategorie->fetchAll() as $categorie) { ?>

            <li>
                <a href="index.php?action=detFilm&id=<?= $categorie['idFilm'] ?>">
                <?= $categorie['titre'] ?></a> <?= $categorie['dateDeSortie'] ?>
            </li>

            <hr class="solid">

        <?php } ?>

</div>

                        <!-- Bouton pour éditer ou supprimer la catégorie -->
<div class="editOrDel">

                            <!-- Lien pour l'édition -->
    <a href='index.php?action=editerCategorie&id=<?= $detailCategorie["idCategorie"] ?>' title="edit"> <i class="fa-solid fa-pen-to-square"></i></a>

                            <!-- Button pour supprimer -->
    <button title="trash" id="alertOn"> <i class="fa-regular fa-trash-can"></i></button>

</div>


                            <!-- POP UP confirmation suppression -->
<div class="alert">
<div class="alertBox">
    <p> Êtes vous sur de vouloir supprimer cette categorie ? </p>
    <div class="alertFlex">
        <button class="alertDisplayOn" id="alertOk"><a href="index.php?action=delCategorie&id=<?= $detailCategorie["idCategorie"] ?>"> Valider </a></button>      
    </div>
</div>
</div>

<?php
    $titrePage = "Movies - Catégorie";
    $backLastPage = "<a href='index.php?action=listCategorie'>Revenir aux catégories</a>";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
<?php 
    ob_start();
?>

<div class="detFilms">

        <!-- Affiche le detail d'un film -->
    <?php 
        $film = $requete->fetch();
        echo 
            "<figure>
                <img src='". $film['affiche'] . "' title='" . $film['titre'] . "'>
            </figure>",
            "<h2>" . $film['titre'] . "</h2>",
            "<p><span class='note noteCenter'>" . $film["note"] . "</span></p>",
            "<p><span class='detail'> Durée : </span> " . $film['dureeFilm'] . "</p>",
            "<p><span class='detail'> Date de sortie : </span> " . $film['dateDeSortie'] . "</p>",
            "<p><span class='detail'> Synopsys : </span> " . $film['synopsis'] . "</p>",
            "<p><span class='detail'> Réalisateur : </span>
                <a href='index.php?action=detPersonne&id=" . $film['idPersonne'] . "'>" . $film['leRealisateur'] . "</a></p>";
    ?>

    <?php
                        // Affiche la liste des catégories du film
        echo "<p><span class='detail'> Catégorie : </span> ";

            foreach($requeteCategorie->fetchAll() as $categorie) { ?>

                <a href="index.php?action=detCategorie&id=<?= $categorie['idCategorie'] ?>">
                <?= $categorie['typeCategorie'] ?>
                </a>&nbsp;

           <?php }
        
        echo "</p>";
    ?>

        <!-- Affiche les roles et leurs acteur dans le film -->

        <div class='listRoleFilm'>
            <h2> Role / Acteur </h2>
                <ul>
        <hr class='solid'>

  <?php      foreach($requeteActeurRole->fetchAll() as $liste) { ?>

            <li class="roleActeur">
                <a href='index.php?action=detRole&id=<?= $liste['idRole'] ?>'> <?= $liste['nomRole'] ?></a> 
                &#x2904;
                <a href='index.php?action=detPersonne&id=<?= $liste['idPersonne'] ?>'> <?= $liste['nomActeurs'] ?></a>

                 <!-- Boutton pour supprimer le film / acteur / role de la table joue -->
                <button title="trash" id="trashActor">
                    <a href="index.php?action=delRoleFilm&id=<?= $film['id_film'] ?>&idActeur=<?= $liste['idActeur'] ?>&idRole=<?= $liste['idRole'] ?>"><i class="fa-regular fa-trash-can"></i></a>
                </button>
            </li>
            <hr class="solid">

       <?php }; echo "</ul></div>"; ?>

</div>

            <!-- Bouton edition et suppression -->
<div class="editOrDel">

                <!-- Lien édition du film -->
    <a href='index.php?action=editerFilm&id=<?= $film['id_film'] ?>' title="edit"> <i class="fa-solid fa-pen-to-square"></i></a>

                <!-- Button suppression du film -->
    <button title="trash" id="alertOn"> <i class="fa-regular fa-trash-can"></i></button>

</div>


                    <!-- POP UP confirmation suppression -->
<div class="alert">
<div class="alertBox">
    <p> Êtes vous sur de vouloir supprimer ce film ? </p>
    <div class="alertFlex">
        <button class="alertDisplayOn" id="alertOk"><a href="index.php?action=delFilm&id=<?= $film['id_film'] ?>"> Valider </a></button>      
    </div>
</div>
</div>

<?php
    $titrePage = "Movies - Films";
    $backLastPage = "<a href='index.php?action=listFilm'>Revenir aux films</a>";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
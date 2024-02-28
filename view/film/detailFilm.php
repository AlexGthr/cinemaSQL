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
            "<p><span class='detail'> Durée : </span> " . $film['dureeFilm'] . "</p>",
            "<p><span class='detail'> Date de sortie : </span> " . $film['dateDeSortie'] . "</p>",
            "<p><span class='detail'> Synopsys : </span> " . $film['synopsis'] . "</p>",
            "<p><span class='detail'> Réalisateur : </span>
                <a href='index.php?action=detPersonne&id=" . $film['idPersonne'] . "'>" . $film['leRealisateur'] . "</a></p>";
    ?>

    <?php

        echo "<p><span class='detail'> Catégorie : </span> ";

            foreach($requeteCategorie->fetchAll() as $categorie) { ?>

                <a href="index.php?action=detCategorie&id=<?= $categorie['idCategorie'] ?>">
                <?= $categorie['typeCategorie'] ?>
                </a>&nbsp;

           <?php }
        
        echo "</p>";
    ?>

        <!-- Affiche les roles et leurs acteur dans le film -->
    <?php 
        echo "<ul> Role / Acteur : ";
        foreach($requeteActeurRole->fetchAll() as $liste) { ?>

            <li class="roleActeur">
                <a href='index.php?action=detRole&id=<?= $liste['idRole'] ?>'> <?= $liste['nomRole'] ?></a> 
                &#x2904;
                <a href='index.php?action=detPersonne&id=<?= $liste['idPersonne'] ?>'> <?= $liste['nomActeurs'] ?></a>
            </li>

       <?php }; echo "</ul>"; ?>

</div>

<?php
    $titrePage = "Movies - Films";
    $backLastPage = "<a href='index.php?action=listFilm'>Revenir aux films</a>";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
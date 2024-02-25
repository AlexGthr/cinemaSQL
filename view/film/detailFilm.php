<?php 
    ob_start();
?>


<div class="listFilms">

    <?php 
        $film = $requete->fetch();
        echo 
            "<figure>
                <img src='". $film['affiche'] . "' title='" . $film['titre'] . "'>
            </figure>",
            "<h2>" . $film['titre'] . "</h2>",
            "<p><span class='detail'> Durée : </span> " . $film['dureeFilm'] . "</p>",
            "<p><span class='detail'> Date de sortie : </span> " . $film['dateDeSortie'] . "</p>",
            "<p><span class='detail'> Synopsys : </span> " . $film['dureeFilm'] . "</p>",
            "<p><span class='detail'> Réalisateur : </span>
                <a href='index.php?action=detPersonne&id=" . $film['idPersonne'] . "'>" . $film['leRealisateur'] . "</a></p>";
    ?>

    <?php 
        echo "<ul> Role / Acteur : ";
        foreach($requeteActeurRole->fetchAll() as $liste) { ?>

            <li class="roleActeur">
                <a href='index.php?action=detrole&id=<?= $liste['idRole'] ?>'> <?= $liste['nomRole'] ?></a> 
                &#x2904;
                <a href='index.php?action=detPersonne&id=<?= $liste['idPersonne'] ?>'> <?= $liste['nomActeurs'] ?></a>
            </li>

       <?php }; echo "</ul>"; ?>

</div>

<?php
    $titrePage = "Movies - Films";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
<?php 
    ob_start();
?>


<div class="detailPersonne">

<?php 
        $personne = $requete->fetch();
        echo "<figure>
                <img class='afficheActeur' src='" . $personne['photo'] . "' title='" . $personne['laPersonne'] . "'>
              </figure>",
              "<h2>" . $personne['laPersonne'] . "</h2>",
              "<p><span class='detail'>Genre : </span>" . $personne['sexe'] . "</p>",
              "<p><span class='detail'>Date de naissance : </span>" . $personne['dateNaissance'] . "</p>";


        if (!empty($personne['idActeur']) && !empty($personne['idRealisateur'])) {

            echo "<p><span class='detail'> Metier : </span> Réalisateur et Acteur </p>";

        } elseif (!empty($personne['idActeur'])) {

            echo "<p><span class='detail'> Metier : </span> Acteur </p>";

        } elseif (!empty($personne['idRealisateur'])) {

            echo "<p><span class='detail'> Metier : </span> Réalisateur </p>";

        }
?>

<?php              // Affichage de la filmographie d'une personne
        if (!empty($personne['idActeur'])) {
        
        echo "<h2 class='DetPersonneTitle'>Filmographie</h2>",
            "<ul><hr class='solid'>";

        $filmographieActeur = $requeteFilmoActeur->fetchAll();
            
        if (!empty($filmographieActeur)) {

        foreach($filmographieActeur as $film) {  ?>

            <li> 
                <a href='index.php?action=detFilm&id=<?= $film['id_film'] ?>'>
                <?= $film['titre'] ?></a> 
                 - <?= $film['dateSortie']; ?>
            </li>         <hr class="solid">

<?php   }

        echo "</ul>";

        echo "<h2 class='DetPersonneTitle'>Role : </h2>",
                "<ul><hr class='solid'>";

        foreach($filmographieActeur as $role) { ?>

            <li>
                <a href='index.php?action=detRole&id=<?= $role['idRole'] ?>'>
                <?= $role['role']?></a>
            </li>         <hr class="solid">

<?php   }

        } else {

            echo "<p class='noFilm'>" . $personne['laPersonne'] . " n'as pas encore joué dans un film.</p><hr class='solid'>";

        }}
        
        if (!empty($personne['idRealisateur'])) {

            echo "<h2 class='DetPersonneTitle'> Film réalisé </h2>",
                    "<ul><hr class='solid'>";

            foreach($requeteFilmoRealisateur->fetchAll() as $filmReal) { ?>

            <li>
                <a href='index.php?action=detFilm&id=<?= $filmReal['id_film']?>'>
                <?= $filmReal['titre']?></a> (<?= $filmReal['date'] ?>)
            </li>         <hr class="solid">

<?php       }
        }?>

</div>

<?php
    $titrePage = "Movies - Detail";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
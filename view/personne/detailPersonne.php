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
        
        echo "<h2>Filmographie</h2>",
            "<ul>";

        $filmographieActeur = $requeteFilmoActeur->fetchAll();
            
        if (!empty($filmographieActeur)) {

        foreach($filmographieActeur as $film) {  ?>

            <li> 
                <a href='index.php?action=detFilm&id=<?= $film['id_film'] ?>'>
                <?= $film['titre'] ?></a> 
                 - <?= $film['dateSortie']; ?>
            </li>

<?php   }

        echo "</ul>";

        echo "<h2>Role : </h2>",
                "<ul>";

        foreach($filmographieActeur as $role) { ?>

            <li>
                <a href='index.php?action=detRole&id=<?= $role['idRole'] ?>'>
                <?= $role['role']?></a>
                &#x2904;
                <a href='index.php?action=detFilm&id=<?= $role['id_film'] ?>'>
                <?= $role['titre']?></a> (<?= $role['dateSortie']?>)
            </li>

<?php   }

        } else {

            echo "<p>" . $personne['laPersonne'] . " n'as pas encore joué dans un film.";

        }}
        
        if (!empty($personne['idRealisateur'])) {

            echo "<h2> Film réalisé </h2>",
                    "<ul>";

            foreach($requeteFilmoRealisateur->fetchAll() as $filmReal) { ?>

            <li>
                <a href='index.php?action=detFilm&id=<?= $filmReal['id_film']?>'>
                <?= $filmReal['titre']?></a> (<?= $filmReal['date'] ?>)
            </li>

<?php       }
        }?>

</div>

<?php
    $titrePage = "Movies - Detail";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
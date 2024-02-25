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
?>

        <h2>Filmographie</h2>
            <ul>

            <?php foreach($requeteDetail->fetchAll() as $film) { ?>
            <li> 
                <a href='index.php?action=detFilm&id=<?= $film['id_film'] ?>'>
                <?= $film['titre'] ?></a> 
                 - <?= $film['dateSortie']; ?>
                 (<a href='index.php?action=detRole&id=<?= $film['id_role']?>'><?= $film['role']?></a>)
            </li>
        <?php } ?>

        </ul>  

</div>

<?php
    $titrePage = "Movies - Detail";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
<?php 
    ob_start();
?>


<div class="detRoles">

        <!-- Affiche la liste des roles -->
    <?php 
        foreach($requete->fetchAll() as $role) { ?>

        <div class="detRole">

            <figure>
                <img src='<?= $role['affiche'] ?>'>
            </figure>

            <h2><a href="index.php?action=detRole&id=<?= $role["idRole"] ?>">
                <?= $role["nomRole"] ?>
            </a></h2>

            <h3>Acteur ayant joué ce role :</h3>

            <p>
                <a href="index.php?action=detPersonne&id=<?= $role['idPersonne']?>">
                <?= $role['nomActeurs'] ?>
                </a>

                dans le film : 

                <a href="index.php?action=detFilm&id=<?= $role['idFilm'] ?>">
                <?= $role['titreFilm'] ?>
                </a>
            </p><br>

        </div>

        <?php } ?>

</div>

<?php
    $titrePage = "Movies - Role";
    $backLastPage = "<a href='index.php?action=listRole'>Revenir aux roles</a>";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
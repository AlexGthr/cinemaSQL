<?php 
    ob_start();
?>


<div class="detRoles">

        <!-- Affiche la liste des roles -->
    <?php 
        foreach($requete->fetchAll() as $role) { ?>


            <figure>
                <img src='<?= $role['affiche'] ?>'>
            </figure>

            <h2><a href="index.php?action=detRole&id=<?= $role["idRole"] ?>">
                <?= $role["nomRole"] ?>
            </a></h2>

            <h3>Acteur ayant joué ce role</h3>

            <p>
                <a href="index.php?action=detPersonne&id=<?= $role['idPersonne']?>">
                <?= $role['nomActeurs'] ?>
                </a>

            dans le film : 

                <a href="index.php?action=detFilm&id=<?= $role['idFilm'] ?>">
                <?= $role['titreFilm'] ?>
                </a>
            </p><br>


        <?php } ?>

</div>

<div class="editOrDel">

    <a href='index.php?action=editerRole&id=<?= $role["idRole"] ?>' title="edit"> <i class="fa-solid fa-pen-to-square"></i></a>

    <button title="trash" id="alertOn"> <i class="fa-regular fa-trash-can"></i></button>

</div>


<div class="alert">
<div class="alertBox">
    <p> Êtes vous sur de vouloir supprimer ce role ? </p>
    <div class="alertFlex">
        <button class="alertDisplayOn" id="alertOk"><a href="index.php?action=delRole&id=<?= $role["idRole"] ?>"> Valider </a></button>      
    </div>
</div>
</div>

<?php
    $titrePage = "Movies - Role";
    $backLastPage = "<a href='index.php?action=listRole'>Revenir aux roles</a>";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
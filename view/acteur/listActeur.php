<?php 
    ob_start();
?>

    <label for="orderby">Trier par :</label>

        <select name="order" id="orderby">
            <option value="">Genre</option>
            <option value="date">Date de naissance</option>
        </select>

<div class="listActeurs">

    <?php 
        foreach($requete->fetchAll() as $acteur) { ?>

        <div class="listActeur">

            <img class="afficheActeur" src='<?= $acteur["photo"] ?>' title='<?= $acteur["acteur"] ?>'>
            <a href="index.php?action=detPersonne&id=<?= $acteur["id_personne"] ?>">
                <p>« <?= $acteur["acteur"] ?> »</p>
            </a>
            <p>Date de naissance : <?= $acteur["dateDeNaissance"] ?></p>

        </div>

        <?php } ?>

</div>

<?php
    $titrePage = "Movies - Acteurs";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
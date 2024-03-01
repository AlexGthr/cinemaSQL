<?php 
    ob_start();
?>
        <!-- Liste déroulante pour faire un trie -->
    <label for="orderby">Trier par :</label>

        <select name="order" id="orderby">
            <option value="">Genre</option>
            <option value="date">Date de naissance</option>
        </select>

        <h1 id="titleSection" class="SectionTitle">Nos Acteurs</h1>

<div class="listActeurs">

        <!-- Affiche la liste des acteurs -->
    <?php 
        foreach($requete->fetchAll() as $acteur) { ?>

        <div class="listActeur">

            <img class="afficheActeur" src='<?= $acteur["photo"] ?>' title='<?= $acteur["acteur"] ?>'>
            <a href="index.php?action=detPersonne&id=<?= $acteur["id_personne"] ?>">
                <p>« <?= $acteur["acteur"] ?> »</p>
            </a>
            <p><span>Date de naissance : </span><?= $acteur["dateDeNaissance"] ?></p>

        </div>

        <?php } ?>

</div>

<?php
    $titrePage = "Movies - Acteurs";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
<?php 
    ob_start();
?>
        <!-- Liste déroulante pour faire un trie -->
    <label for="orderby">Trier par :</label>

        <select name="order" id="orderby">
            <option value="">Genre</option>
            <option value="date">Date de naissance</option>
        </select>

<div class="listRealisateurs">

        <!-- Affiche la liste des réalisateurs -->
    <?php 
        foreach($requete->fetchAll() as $realisateur) { ?>

        <div class="listRealisateur">

            <img class="afficheActeur" src='<?= $realisateur["photo"] ?>' title='<?= $realisateur["leRealisateur"] ?>'>
            <a href="index.php?action=detPersonne&id=<?= $realisateur["id_personne"] ?>">
                <p>« <?= $realisateur["leRealisateur"] ?> »</p>
            </a>
            <p>Date de naissance : <?= $realisateur["dateDeNaissance"] ?></p>

        </div>

        <?php } ?>

</div>

<?php
    $titrePage = "Movies - Réalisateur";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
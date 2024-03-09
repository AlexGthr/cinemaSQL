<?php 
    ob_start();
?>

<?php 
    if(isset($_GET['order'])) {

        $isSelectGenre = $_GET['order'] === "genre" ? true : false;
        $isSelectDate = $_GET['order'] === "date" ? true : false;
    }

    else {
        $isSelectGenre = false;
        $isSelectDate = false;
    }
?>

        <!-- Liste déroulante pour faire un trie d'affichage -->
        <form id="formulaireTrieReal" action="index.php" method="get">
    <label for="orderby">Trier par :</label>

        <select name="order" id="orderby">
            <option value="genre" <?= $isSelectGenre ? 'selected' : '' ?> >Genre</option>
            <option value="date" <?= $isSelectDate ? 'selected' : '' ?>>Date de naissance</option>
        </select>
    <button type="button" onclick="redirigerTrieReal()">Valider</button>
</form>

        <h1 id="titleSection" class="SectionTitle">Nos Réalisateurs</h1>

<div class="listRealisateurs">

        <!-- Affiche la liste des réalisateurs -->
    <?php 
        foreach($requete->fetchAll() as $realisateur) { ?>

        <div class="listRealisateur">

        <a href="index.php?action=detPersonne&id=<?= $realisateur["id_personne"] ?>">
            <img class="afficheActeur" src='<?= $realisateur["photo"] ?>' title='<?= $realisateur["leRealisateur"] ?>'>
        </a>
        
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
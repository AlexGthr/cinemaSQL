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
<form id="formulaireTrieActeur" action="index.php" method="get">
    <label for="orderby">Trier par :</label>

        <select name="order" id="orderby">
            <option value="genre" <?= $isSelectGenre ? 'selected' : '' ?> >Genre</option>
            <option value="date" <?= $isSelectDate ? 'selected' : '' ?>>Date de naissance</option>
        </select>
    <button type="button" onclick="redirigerTrieActeur()">Valider</button>
</form>

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
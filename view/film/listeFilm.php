<?php 
    ob_start();
?>
<?php 
    if(isset($_GET['order'])) {

        $isSelectDate = $_GET['order'] === "date" ? true : false;
        $isSelectduree = $_GET['order'] === "duree" ? true : false;
        $isSelectnote = $_GET['order'] === "note" ? true : false;
    }

    else {
        $isSelectDate = false;
        $isSelectduree = false;
        $isSelectnote = false;
    }
?>

        <!-- Liste déroulante pour faire un trie d'affichage -->
<form id="formulaireTrieFilm" action="index.php" method="get">
    <label for="orderby">Trier par :</label>

        <select name="order" id="orderby">
            <option value="date" <?= $isSelectDate ? 'selected' : '' ?> >Date de sortie</option>
            <option value="duree" <?= $isSelectduree ? 'selected' : '' ?>>Durée</option>
            <option value="note" <?= $isSelectnote ? 'selected' : '' ?>>Note</option>
        </select>
    <button type="button" onclick="redirigerTrieFilm()">Valider</button>
</form>

        <h1 id="titleSection" class="SectionTitle">Nos films</h1>

<div class="listFilms">


        <!-- Affiche la description d'un film, affiche/titre/date de sortie/durée/note -->
    <?php 
        foreach($requete->fetchAll() as $film) { ?>

        <div class="acceuilFilm">

            <a href="index.php?action=detFilm&id=<?= $film["id_film"] ?>">
            <img src='<?= $film["affiche"] ?>' class="afficheFilm" title='<?= $film["titre"] ?>'>
                <p id="titleFilm"><?= $film["titre"] ?></p>
            </a>
            <p><span class="note"> <?= $film["note"] ?></span></p>
            <p><span>Durée :</span> <?= $film["dureeFilm"] ?></p>
            <p><span>Date de sortie :</span> <?= $film["dateDeSortie"] ?></p>

        </div>

        <?php } ?>

</div>

<?php
    $titrePage = "Movies - Films";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
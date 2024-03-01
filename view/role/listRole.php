<?php 
    ob_start();
?>
        <!-- Liste déroulante pour faire un trie -->
    <label for="orderby">Trier par :</label>

        <select name="order" id="orderby">
            <option value="">Genre</option>
            <option value="date">Date</option>
        </select>

        <h1 id="titleSection" class="SectionTitle">Nos roles</h1>

<div class="listRoles">

        <!-- Affiche la liste des roles -->
    <?php 
        foreach($requete->fetchAll() as $role) { ?>

        <div class="listRole">

        <hr class="solid">

            <h2><a href="index.php?action=detRole&id=<?= $role["idRole"] ?>">
                <?= $role["nomRole"] ?>
            </a></h2>

            <h3>Acteur ayant joué ce role</h3>

            <p>
                <a href="index.php?action=detPersonne&id=<?= $role['idPersonne']?>">
                <?= $role['nomActeurs'] ?>
                </a>
            </p><br>

        </div>

        <hr class="solid">

        <?php } ?>

</div>

<?php
    $titrePage = "Movies - Role";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
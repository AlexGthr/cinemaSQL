<?php 
    ob_start();
?>
        <!-- Liste déroulante pour faire un trie -->
    <label for="orderby">Trier par :</label>

        <select name="order" id="orderby">
            <option value="">Genre</option>
            <option value="date">Date</option>
        </select>

<div class="listRoles">

        <!-- Affiche la liste des acteurs -->
    <?php 
        foreach($requete->fetchAll() as $role) { ?>

        <div class="listRole">

            <h2><a href="index.php?action=detRole&id=<?= $role["idRole"] ?>">
                <?= $role["nomRole"] ?>
            </a></h2>

            <h3>Acteur ayant joué ce role :</h3>

            <p>
                <a href="index.php?action=detPersonne&id=<?= $role['idPersonne']?>">
                <?= $role['nomActeurs'] ?>
                </a>
            </p><br>

        </div>

        <?php } ?>

</div>

<?php
    $titrePage = "Movies - Role";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
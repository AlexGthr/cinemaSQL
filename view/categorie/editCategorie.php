<?php 
    session_start();
    ob_start();
?>

<?php
        if (isset($_SESSION['message'] )){
            echo $_SESSION['message'] ;
            unset($_SESSION['message'] );
        }
?>

        <?php $categorie = $requete->fetch(); ?>

    <h3 class="titleFormulaire"> Modifier une catégorie : </h3>

    <form action="index.php?action=editCategorie&id=<?= $categorie['id_categorie']?>" method="post" enctype="multipart/form-data"> <!-- Formulaire pour envoyer un produit -->

    <div class="caseFormulaire">
    <p>
        <label> 
            Nom de la catégorie :
            <input type="text" name="categorie" value="<?= $categorie['type'] ?>" required>
        </label>
    </p>
</div>

    <div class="validation">
        <p>
            <input type="submit" name="submit" value="Ajouter une categorie">
        </p>
    </div>
</div>

</form>



<?php
    $titrePage = "Movies - Gestion";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
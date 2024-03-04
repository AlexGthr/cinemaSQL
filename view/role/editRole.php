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

    <h3 class="titleFormulaire"> Modifier un role : </h3>

    <?php $role = $requete->fetch(); ?>

    <form action="index.php?action=editRole&id=<?= $role['id_role'] ?>" method="post" enctype="multipart/form-data"> <!-- Formulaire pour envoyer un produit -->

    <div class="caseFormulaire">
    <p>
        <label> 
            Nom du role :
            <input type="text" name="role" value="<?= $role['nom'] ?>" required>
        </label>
    </p>
    </div>

    <div class="validation">
        <p>
            <input type="submit" name="submit" value="Modifier le role">
        </p>
    </div>

</form>



<?php
    $titrePage = "Movies - Gestion";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
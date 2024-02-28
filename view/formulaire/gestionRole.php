<?php 
    session_start();
    ob_start();
?>
    <div class="listeFormulaire">

        
    <form id="monFormulaire" action="index.php" method="get">

            <label for="choixFormulaire">Ajouter :</label>

            <select name="choix" id="choixFormulaire">
                <option value="Role">Role</option>
                <option value="Personne">Personne</option>
                <option value="Film">Film</option>
                <option value="Categorie">Catégorie</option>
                <option value="Casting">Casting</option>
            </select>

            <button type="button" onclick="rediriger()">Valider</button>
        </form>

    </div>

    <h3> Ajouter un role : </h3>

    <form action="index.php?action=addRole" method="post" enctype="multipart/form-data"> <!-- Formulaire pour envoyer un produit -->

<div>
    <p>
        <label> 
            Nom du role :
            <input type="text" name="role" required>
        </label>
    </p>

    <div class="validation">
        <p>
            <input type="submit" name="submit" value="Ajouter un role">
        </p>
    </div>
</div>

</form>

<?php
        if (isset($_SESSION['message'] )){
            echo $_SESSION['message'] ;
            unset($_SESSION['message'] );
        }
?>


<?php
    $titrePage = "Movies - Gestion";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
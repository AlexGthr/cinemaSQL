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
            </select>

            <button type="button" onclick="rediriger()">Valider</button>
        </form>

    </div>

    <h3> Ajouter une personne : </h3>

    <form action="index.php?action=addPersonne" method="post" enctype="multipart/form-data"> <!-- Formulaire pour envoyer un produit -->

<div>
    <div class="caseFormulaire">
    <p>
        <label> 
            Nom :
            <input type="text" name="nom" required>
        </label>
    </p>
    </div>

    <div class="caseFormulaire">
    <p>
        <label> 
            Prénom :
            <input type="text" name="nom" required>
        </label>
    </p>
    </div>

    <div class="caseFormulaire">
    <p>
                <label> 
                    Genre :
                    <select name="genre" required>
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                    </select>
                </label>
            </p>
    </div>

    <div class="caseFormulaire">
    <p>
                <label> 
                    Date de Naissance :
                    <input type="date" name="date_naissance" required>
                </label>
            </p>
    </div>

    <div class="caseFormulaire">
            <!-- Ajout d'une photo -->
    </div>

    <div class="validation">
        <p>
            <input type="submit" name="submit" value="Ajouter une personne">
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
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
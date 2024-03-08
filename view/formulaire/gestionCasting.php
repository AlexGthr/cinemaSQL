<?php 
    
    ob_start();
?>
    <div class="listeFormulaire">

        
    <form id="monFormulaire" action="index.php" method="get">

            <label for="choixFormulaire">Ajouter :</label>

            <select name="choix" id="choixFormulaire">
                <option value="Casting">Casting</option>
                <option value="Personne">Personne</option>
                <option value="Film">Film</option>
                <option value="Role">Role</option>
                <option value="Categorie">Catégorie</option>
            </select>

            <button type="button" onclick="rediriger()">Valider</button>
        </form>

    </div>

    <h3 class="titleFormulaire"> Ajouter un casting : </h3>

    <form action="index.php?action=addCasting" method="post" enctype="multipart/form-data"> <!-- Formulaire pour envoyer un produit -->

    <div class="caseFormulaire">
    <p>
        <label>
            Film :
            <select name="film" required>

                <?php foreach($requeteFilm->fetchall() as $film) { ?>

                    <option value="<?= $film['idFilm'] ?>"> 
                            <?=$film['titre'] ?>
                    </option>

              <?php  } ?>

            </select>
        </label>
    </p>
    </div>

    <div class="caseFormulaire">
    <p>
        <label>
            Acteur :
            <select name="acteur" required>

                <?php foreach($requeteActeur->fetchall() as $acteur) { ?>

                    <option value="<?= $acteur['idActeur'] ?>"> 
                            <?=$acteur['lesActeurs'] ?>
                    </option>

              <?php  } ?>

            </select>
        </label>
    </p>
    </div>

    <div class="caseFormulaire">
    <p>
        <label>
            Role :
            <select name="role" required>

                <?php foreach($requeteRole->fetchall() as $role) { ?>

                    <option value="<?= $role['idRole'] ?>"> 
                            <?=$role['nom'] ?>
                    </option>

              <?php  } ?>

            </select>
        </label>
    </p>
    </div>

    <div class="validation">
        <p>
            <input type="submit" name="submit" value="Ajouter un casting">
        </p>
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
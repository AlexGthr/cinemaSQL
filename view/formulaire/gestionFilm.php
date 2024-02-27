<?php 
    session_start();
    ob_start();
?>
    <div class="listeFormulaire">

        
    <form id="monFormulaire" action="index.php" method="get">

            <label for="choixFormulaire">Ajouter :</label>

            <select name="choix" id="choixFormulaire">
                <option value="Film">Film</option>
                <option value="Personne">Personne</option>
                <option value="Role">Role</option>
                <option value="Categorie">Catégorie</option>
            </select>

            <button type="button" onclick="rediriger()">Valider</button>
        </form>

    </div>

    <h3> Ajouter une personne : </h3>

    <form id="personne" action="index.php?action=addFilm" method="post" enctype="multipart/form-data"> <!-- Formulaire pour envoyer un produit -->

<div>
    <div class="caseFormulaire">
    <p>
        <label> 
            Titre du film :
            <input type="text" name="titre" required>
        </label>
    </p>
    </div>

    <div class="caseFormulaire">
    <p>
                <label> 
                    Date de sortie :
                    <input type="date" name="dateSortie" required>
                </label>
            </p>
    </div>

    <div class="caseFormulaire">
    <p>
        <label> 
            Durée (En minute) :
            <input type="number" name="duree" required>
        </label>
    </p>
    </div>

    <div class="caseFormulaire">
    <p>
                <label> 
                    Synopsys (255 caractères max):
                    <textarea name="synopsys" placeholder="Votre texte ici..."></textarea>
                </label>
            </p>
    </div>

    <div class="caseFormulaire">
    <p>
        <label> 
            Note :
            <select name="note" required>
                <option value="0" select>0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </label>
    </p>
    </div>

    <div class="caseFormulaire">
    <p>
        Affiche (Format : jpg, png, jpeg - 1MO max):
        <label>
            <input type="file" name="file">
        </label>
    </p>
    </div>

    <div class="caseFormulaire">
    <p>
        Réalisateur :
        <label>
            <select name="realisateur" required>

                <?php foreach($requeteReal->fetchall() as $real) { ?>

                    <option value="<?= $real['idRealisateur'] ?>"> 
                            <?=$real['lesRealisateur'] ?>
                    </option>

              <?php  } ?>

            </select>
        </label>
    </p>
    </div>

    <div class="caseFormulaire">
    <p>
        Catégorie :
        <label>

                <?php foreach($requeteCategorie->fetchall() as $categorie) { ?>
                    <br>
                    <input type="checkbox" name="categorie[]" value="<?= $categorie['idCategorie'] ?>"> <?= $categorie['typeCategorie'] ?>
                    
              <?php  } ?>

        </label>
    </p>
    </div>

    <div class="validation">
        <p>
            <input type="submit" name="submit" value="Ajouter un film">
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
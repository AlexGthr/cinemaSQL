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

                    <!-- Page modification d'un film -->

<h3 class="titleFormulaire"> Modifier un film : </h3>

 <!-- Je récupère les éléments de mon film -->
<?php $film = $requete->fetch(); ?>


                        <!-- Formulaire pour envoyer un produit -->
<form id="personne" action="index.php?action=editFilm&id=<?= $film['id_film']?>" method="post" enctype="multipart/form-data">



<div class="caseFormulaire">
<p>
    <label> 
        Titre du film :
        <input type="text" name="titre" value="<?= $film['titre']?>" required>
    </label>
</p>
</div>

<div class="caseFormulaire">
<p>
            <label> 
                Date de sortie :
                <input type="date" name="dateSortie" value="<?= $film['dateDeSortie']?>" required>
            </label>
        </p>
</div>

<div class="caseFormulaire">
<p>
    <label> 
        Durée (En minute) :
        <input type="number" name="duree" value="<?= $film['duree']?>" required>
    </label>
</p>
</div>

<div class="caseFormulaire">
<p>
            <label> 
                Synopsis :
                <textarea name="synopsys" placeholder="Votre texte ici..." rows="5"><?= $film['synopsis']?></textarea>
            </label>
        </p>
</div>

<div class="caseFormulaire">
<p>
    <label> 
        Note :
        <select name="note" required>
            <?php for ($i = 0; $i <= 5; $i++)
            {
                if ($i === (int)$film['note']) {
                    echo "<option value=". round($film['note']) ." selected>" . round($film['note']) . "</option>";
                } else {
                    echo "<option value=". $i .">" . $i . "</option>";
                }
            } ?>
        </select>
    </label>
</p>
</div>

<div class="caseFormulaire">
<p>
    <label>
        Affiche (Format : jpg, png, jpeg, webp - 1MO max):
        <input type="file" name="file">
    </label>
</p>
</div>

<div class="caseFormulaire">
<p>
    <label>
        Réalisateur :

                            <!-- Requete pour afficher en premier le réalisateur du film en selected puis les autres réalisateurs  -->
        <select name="realisateur" required>

            <?php foreach($requeteReal->fetchall() as $real) { ?>

                <?php if ($real['idRealisateur'] === $film['idReal']) { ?>
                    <option value="<?= $real['idRealisateur'] ?>" selected> 
                            <?=$real['lesRealisateur'] ?>
                    </option>
                <?php } else { ?>

                    <option value="<?= $real['idRealisateur'] ?>"> 
                            <?=$real['lesRealisateur'] ?>
                    </option>

               <?php } ?>

          <?php  } ?>

        </select>
    </label>
</p>
</div>

<div class="caseFormulaireCheckbox">
    
<p>
    <legend class='checkboxForm'>
        Catégorie :
    </legend>

<?php    

 // Je récupère les catégories de mon film
$filmCategories = $requeteCategoriesFilm->fetchAll();

// Puis je fais une boucle pour tester si les catégories du film correspondent à des catégories de la table categorie
foreach ($requeteCategorie->fetchAll() as $categorie) { 
    $isChecked = in_array($categorie['idCategorie'], array_column($filmCategories, 'id_categorie'));
?>
    <br>
    <div class='checkboxAll'>                                                                                                          <!-- Si checked est "true", alors checked -->
        <input type="checkbox" name="categorie[]" id='<?= $categorie['typeCategorie'] ?>' value="<?= $categorie['idCategorie'] ?>" <?= $isChecked ? 'checked' : '' ?>>
        <label for='<?= $categorie['typeCategorie'] ?>'> <?= $categorie['typeCategorie'] ?>  </label>
    </div>

<?php } ?>

<div class="validation">
    <p>
        <input type="submit" name="submit" value="Editer un film">
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
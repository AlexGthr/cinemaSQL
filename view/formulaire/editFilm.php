<?php 
    ob_start();
?>


<h3 class="titleFormulaire"> Modifier un film : </h3>

<form id="personne" action="index.php?action=addFilm" method="post" enctype="multipart/form-data"> <!-- Formulaire pour envoyer un produit -->

<?php $film = $requete->fetch(); ?>

<div>
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
        Affiche (Format : jpg, png, jpeg - 1MO max):
        <input type="file" name="file">
    </label>
</p>
</div>

<div class="caseFormulaire">
<p>
    <label>
        Réalisateur :
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

            <?php foreach($requeteCategorie->fetchall() as $categorie) { ?>
            
            <?php if($categorie['idCategorie'] === $film['idCateg']) {
                $isChecked = true;
            }
            else {
                $isChecked = false;
            } ?>

            <br><div class='checkboxAll'>
                <input type="checkbox" name="categorie[]" id='<?= $categorie['typeCategorie'] ?>' value="<?= $categorie['idCategorie'] ?>" <?= $isChecked ?>>
                 <label for='<?= $categorie['typeCategorie'] ?>'> <?= $categorie['typeCategorie'] ?>  </label>
            </div>
                
          <?php  } ?>
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
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
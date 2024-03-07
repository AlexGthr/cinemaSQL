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

    </div>

    <h3 class="titleFormulaire"> Modifier personne : </h3>

    <?php $personne = $requete->fetch(); ?>

    <form id="personne" action="index.php?action=editPersonne&id=<?= $personne['id_personne']?>&type=" method="post" enctype="multipart/form-data"> <!-- Formulaire pour envoyer un produit -->

<div>
    <div class="caseFormulaire">
    <p>
        <label> 
            Nom :
            <input type="text" name="last" value="<?= $personne["nom"] ?>" required>
        </label>
    </p>
    </div>

    <div class="caseFormulaire">
    <p>
        <label> 
            Prénom :
            <input type="text" name="name" value="<?= $personne["prenom"] ?>"required>
        </label>
    </p>
    </div>

    <div class="caseFormulaire">
    <p>
                <label> 
                    Genre :
                    <select name="genre" required>
                        <?php if ($personne["sexe"] === "M") { ?>

                        <option value="M" select>Masculin</option>
                        <option value="F">Féminin</option>

                        <?php } else { ?>

                        <option value="M">Masculin</option>
                        <option value="F" selected>Féminin</option>

                        <?php } ?>

                    </select>
                </label>
            </p>
    </div>

    <div class="caseFormulaire">
    <p>
                <label> 
                    Date de Naissance :
                    <input type="date" name="dateNaissance" value="<?= $personne["dateDeNaissance"] ?>" required>
                </label>
            </p>
    </div>

    <div class="caseFormulaire">
    <p>
        <label>
            Photo (Format : jpg, png, jpeg, webp - 1MO max):
            <input type="file" name="file">
        </label>
    </p>
    </div>

    <div class="caseFormulaire">
    <p>
                <label> 
                    Metier :
                    <select name="metier" required>
                            
                    <?php if (!empty($personne['idActeur']) && !empty($personne['idRealisateur'])) { ?>

                        <option value="acteur" >Acteur</option>
                        <option value="realisateur">Réalisateur</option>
                        <option value="lesdeux" selected>Les deux</option>

                    <?php } elseif (!empty($personne['idActeur'])) { ?>

                        <option value="acteur" selected>Acteur</option>
                        <option value="realisateur">Réalisateur</option>
                        <option value="lesdeux">Les deux</option>

                    <?php } elseif (!empty($personne['idRealisateur'])) { ?>

                        <option value="acteur" >Acteur</option>
                        <option value="realisateur" selected>Réalisateur</option>
                        <option value="lesdeux">Les deux</option>

                    <?php } ?>

                    </select>
                </label>
            </p>
    </div>

    <div class="validation">
        <p>
            <input type="submit" name="submit" value="Editer une personne">
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
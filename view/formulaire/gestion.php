<?php 
    ob_start();
?>
<div class='wrapperGestion'>

<h1 id="titleSection" class="SectionTitle">Gestion</h1>


    <div class="listeFormulaire">

        
    <form id="monFormulaire" action="index.php" method="get">

            <label for="choixFormulaire">Ajouter :</label>

            <select name="choix" id="choixFormulaire">
                <option value="Personne">Personne</option>
                <option value="Film">Film</option>
                <option value="Role">Role</option>
                <option value="Categorie">Catégorie</option>
                <option value="Casting">Casting</option>
            </select>

            <button type="button" onclick="rediriger()">Valider</button>
        </form>

    </div>

    <h3> Merci de faire un choix dans la liste </h3>

    <figure>
        <img src='public/img/thinker.png' title='Thinker waiting'>
    </figure>
</div>
<?php
    $titrePage = "Movies - Gestion";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
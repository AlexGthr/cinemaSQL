<?php
    ob_start();
?>

<h1>Se connecter</h1>

    <form action="index.php?action=login" method="POST">

        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo"><br>

        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password"><br>

        <input type="submit" name="submit" value="Se connecter">
    </form>


<?php
        if (isset($_SESSION['message'] )){
            echo $_SESSION['message'] ;
            unset($_SESSION['message'] );
        }
?>

<?php
    $titrePage = "Movies - Connexion";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
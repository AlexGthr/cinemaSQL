<?php
    ob_start();
?>

<h1>S'incrire</h1>

<form action="index.php?action=addUser" method="POST">

    <label for="pseudo">Pseudo</label>
    <input type="text" name="pseudo" id="pseudo" required><br>

    <label for="email">Mail</label>
    <input type="email" name="email" id="email" required><br>

    <label for="pass1">Mot de passe</label>
    <input type="password" name="pass1" id="pass1" required><br>

    <label for="pass2">Confirmation du mot de passe</label>
    <input type="password" name="pass2" id="pass2" required><br>

    <input type="submit" name="submit" value="S'enrengistrer">
</form>

<?php
        if (isset($_SESSION['message'] )){
            echo $_SESSION['message'] ;
            unset($_SESSION['message'] );
        }
?>

<?php
    $titrePage = "Movies - Inscription";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>


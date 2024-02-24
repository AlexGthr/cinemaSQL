<?php 
    ob_start();
?>


<div class="listFilms">

    <?php 
        $film = $requete->fetch();
        echo $film["titre"];
    ?>

</div>

<?php
    $titrePage = "Movies - Films";
    $content = ob_get_clean();

    require_once "view/template.php";  
?>
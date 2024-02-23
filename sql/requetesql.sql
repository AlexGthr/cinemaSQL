<?php 
    require_once('databaseConnect.php');
?>

<?php

$afficheEtTitreFilm = 
'SELECT
	film.affiche,
	film.titre
FROM film';

$recipesAfficheEtTitreFilm = $mysqlClient->prepare($afficheEtTitreFilm);
$recipesAfficheEtTitreFilm->execute();

?>
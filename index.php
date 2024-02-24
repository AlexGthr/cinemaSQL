<?php

use Controller\CinemaController;  // Utilise le Controller : CinemaController

// Je récupère les classes existante dans mon document
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';

});

$ctlrCinema = new CinemaController(); // Je crée un nouvel objet dans le controller

if (isset($_GET["action"])) {  // Si j'ai une action dans l'url alors :

    $id = (isset($_GET["id"])) ? $_GET["id"] : null; // Récupère l'id si il existe

    switch ($_GET["action"]) {  // Et j'effectue un switch suivant l'action :

        case "acceuil": $ctlrCinema->afficheTitrefilm(); break;
        case "listFilm": $ctlrCinema->listFilm(); break;
        case "detFilm": $ctlrCinema->detFilm($id); break;
    }
}
else {  // Sinon, j'affiche la page d'acceuil
    $ctlrCinema->afficheTitrefilm();
}

?>
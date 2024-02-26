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

        case "acceuil": $ctlrCinema->afficheTitrefilm(); break; // Affiche la vue acceuil
        case "listFilm": $ctlrCinema->listFilm(); break;  // Affiche la vue listeFilm
        case "listActeur": $ctlrCinema->listActeur(); break; // Affiche la vue Liste Acteur
        case "listReal": $ctlrCinema->listRealisateur(); break; // Affiche la vue Liste Réalisateur
        case "listRole": $ctlrCinema->listRole(); break; // Affiche la vue Liste Role
        case "listCategorie": $ctlrCinema->listCategorie(); break; // Affiche la vue Liste Catégorie
        case "detFilm": $ctlrCinema->detFilm($id); break; // Affiche la vue Detail Film
        case "detPersonne": $ctlrCinema->detPersonne($id); break; // Affiche la vue detail Personne
        case "detRole": $ctlrCinema->detRole($id); break; // Affiche la vue Detail role
        case "detCategorie": $ctlrCinema->detCategorie($id); break; // affiche la vue Detail Catégorie
        
        case "gestion": $ctlrCinema->gestion(); break; // Affiche la vue GESTION

        case "gestionRole" : $ctlrCinema->gestionRole(); break; // Affiche la vue gestion Role
        case "gestionCategorie" : $ctlrCinema->gestionCategorie(); break; // Affiche la vue gestion Categorie
        case "gestionPersonne": $ctlrCinema->gestionPersonne(); break;

        case "addRole" : $ctlrCinema->addRole(); break; // Permet l'ajout d'un role
        case "addCategorie": $ctlrCinema->addCategorie(); break; // Permet l'ajout d'une catégorie
    }
}
else {  // Sinon, j'affiche la page d'acceuil
    $ctlrCinema->afficheTitrefilm();
}

?>
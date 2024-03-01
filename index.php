<?php

use Controller\CinemaController;  // Utilise le Controller : CinemaController
use Controller\FilmController;
use Controller\PersonneController;

// Je récupère les classes existante dans mon document
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';

});

$ctlrCinema = new CinemaController();
$ctlrFilm = new FilmController();
$ctlrPersonne = new PersonneController();

if (isset($_GET["action"])) {  // Si j'ai une action dans l'url alors :

    $id = (isset($_GET["id"])) ? $_GET["id"] : null; // Récupère l'id si il existe

    switch ($_GET["action"]) {  // Et j'effectue un switch suivant l'action :

        case "acceuil": $ctlrCinema->afficheTitrefilm(); break; // Affiche la vue acceuil

        case "listFilm": $ctlrFilm->listFilm(); break;  // Affiche la vue listeFilm
        case "detFilm": $ctlrFilm->detFilm($id); break; // Affiche la vue Detail Film
        case "gestionFilm": $ctlrFilm->gestionFilm(); break;
        case "addFilm": $ctlrFilm->addFilm(); break;

        case "listActeur": $ctlrPersonne->listActeur(); break; // Affiche la vue Liste Acteur
        case "listReal": $ctlrPersonne->listRealisateur(); break; // Affiche la vue Liste Réalisateur
        case "detPersonne": $ctlrPersonne->detPersonne($id); break; // Affiche la vue detail Personne
        case "gestionPersonne": $ctlrPersonne->gestionPersonne(); break;
        case "addPersonne": $ctlrPersonne->addPersonne(); break;

        case "listRole": $ctlrCinema->listRole(); break; // Affiche la vue Liste Role
        case "listCategorie": $ctlrCinema->listCategorie(); break; // Affiche la vue Liste Catégorie
        case "detRole": $ctlrCinema->detRole($id); break; // Affiche la vue Detail role
        case "detCategorie": $ctlrCinema->detCategorie($id); break; // affiche la vue Detail Catégorie
        
        case "gestion": $ctlrCinema->gestion(); break; // Affiche la vue GESTION

        case "gestionRole" : $ctlrCinema->gestionRole(); break; // Affiche la vue gestion Role
        case "gestionCategorie" : $ctlrCinema->gestionCategorie(); break; // Affiche la vue gestion Categorie
        case "gestionCasting": $ctlrCinema->gestionCasting(); break;

        case "addRole" : $ctlrCinema->addRole(); break; // Permet l'ajout d'un role
        case "addCategorie": $ctlrCinema->addCategorie(); break; // Permet l'ajout d'une catégorie
        case "addCasting": $ctlrCinema->addCasting(); break;
    }
}
else {  // Sinon, j'affiche la page d'acceuil
    $ctlrCinema->afficheTitrefilm();
}

?>
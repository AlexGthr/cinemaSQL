<?php

use Controller\CinemaController;  // Utilise le Controller : CinemaController
use Controller\FilmController;
use Controller\PersonneController;
use Controller\RoleController;
use Controller\CategorieController;
use Controller\CastingController;

// Je récupère les classes existante dans mon document
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';

});

$ctlrCinema = new CinemaController();
$ctlrFilm = new FilmController();
$ctlrPersonne = new PersonneController();
$ctlrRole = new RoleController();
$ctlrCategorie = new CategorieController();
$ctlrCasting = new CastingController();

if (isset($_GET["action"])) {  // Si j'ai une action dans l'url alors :

    $id = (isset($_GET["id"])) ? $_GET["id"] : null; // Récupère l'id si il existe

    switch ($_GET["action"]) {  // Et j'effectue un switch suivant l'action :

        case "acceuil": $ctlrCinema->afficheTitrefilm(); break; // Affiche la vue acceuil
        case "gestion": $ctlrCinema->gestion(); break; // Affiche la vue GESTION

        case "listFilm": $ctlrFilm->listFilm(); break;  // Affiche la vue listeFilm
        case "detFilm": $ctlrFilm->detFilm($id); break; // Affiche la vue Detail Film
        case "gestionFilm": $ctlrFilm->gestionFilm(); break;
        case "addFilm": $ctlrFilm->addFilm(); break;
        case "editerFilm": $ctlrFilm->editFilms($id); break;

        case "listActeur": $ctlrPersonne->listActeur(); break; // Affiche la vue Liste Acteur
        case "listReal": $ctlrPersonne->listRealisateur(); break; // Affiche la vue Liste Réalisateur
        case "detPersonne": $ctlrPersonne->detPersonne($id); break; // Affiche la vue detail Personne
        case "gestionPersonne": $ctlrPersonne->gestionPersonne(); break;
        case "addPersonne": $ctlrPersonne->addPersonne(); break;
        
        case "listRole": $ctlrRole->listRole(); break; // Affiche la vue Liste Role
        case "detRole": $ctlrRole->detRole($id); break; // Affiche la vue Detail role
        case "gestionRole" : $ctlrRole->gestionRole(); break; // Affiche la vue gestion Role
        case "addRole" : $ctlrRole->addRole(); break; // Permet l'ajout d'un role

        case "listCategorie": $ctlrCategorie->listCategorie(); break; // Affiche la vue Liste Catégorie
        case "detCategorie": $ctlrCategorie->detCategorie($id); break; // affiche la vue Detail Catégorie
        case "gestionCategorie" : $ctlrCategorie->gestionCategorie(); break; // Affiche la vue gestion Categorie
        case "addCategorie": $ctlrCategorie->addCategorie(); break; // Permet l'ajout d'une catégorie

        case "gestionCasting": $ctlrCasting->gestionCasting(); break;
        case "addCasting": $ctlrCasting->addCasting(); break;
        


    }
}
else {  // Sinon, j'affiche la page d'acceuil
    $ctlrCinema->afficheTitrefilm();
}

?>
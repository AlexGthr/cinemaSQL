<?php
session_start();
use Controller\CinemaController;        // Utilise le Controller : CinemaController qui s'occupe functions "Acceuil" et "Gestion" (formulaire)
use Controller\FilmController;          // Utilise le Controller : FilmController qui s'occupe des functions lié au films
use Controller\PersonneController;      // Utilise le Controller : PersonneController qui s'occupe des functions lié au Personnes (acteur/réalisateur)
use Controller\RoleController;          // Utilise le Controller : RoleController qui s'occupe des functions lié au Role
use Controller\CategorieController;     // Utilise le Controller : CategorieController qui s'occupe des functions lié au catégories de film
use Controller\CastingController;       // Utilise le Controller : CastingController qui s'occupe des functions lié au casting d'un film (film->acteur->role)

// Je récupère les classes existante dans mon document
spl_autoload_register(function ($class_name) {
    require str_replace("\\", DIRECTORY_SEPARATOR, $class_name) . '.php';

});


// Je crée mes controller
$ctlrCinema = new CinemaController();
$ctlrFilm = new FilmController();
$ctlrPersonne = new PersonneController();
$ctlrRole = new RoleController();
$ctlrCategorie = new CategorieController();
$ctlrCasting = new CastingController();


if (isset($_GET["action"])) {  // Si j'ai une action dans l'url alors :

    $id = (isset($_GET["id"])) ? $_GET["id"] : null; // Je récupère l'id si il existe

    switch ($_GET["action"]) {  // Et j'effectue un switch suivant l'action :

        case "acceuil": $ctlrCinema->afficheTitrefilm(); break; // Affiche la vue acceuil
        case "gestion": $ctlrCinema->gestion(); break;
        case "research": $ctlrCinema->research(); break; // Affiche la vue GESTION

        case "listFilm": $ctlrFilm->listFilm(); break;  // Affiche la vue listeFilm
        case "detFilm": $ctlrFilm->detFilm($id); break; // Affiche la vue Detail Film
        case "gestionFilm": $ctlrFilm->gestionFilm(); break; // Affiche la vue gestion Film (formulaire)
        case "addFilm": $ctlrFilm->addFilm(); break; // Effectue l'ajout de Film (traitement d'un ajout)
        case "editerFilm": $ctlrFilm->editerFilm($id); break; // Affiche la vue editer un film (formulaire)
        case "editFilm": $ctlrFilm->editFilm($id); break; // Effectue l'edit d'un film (traitement)
        case "delFilm": $ctlrFilm->delFilm($id); break; // Effectue la suppression d'un film (traitement)
        case "delRoleFilm": $ctlrFilm->delRoleFilm($id); break; // supprime le casting d'un film (film->acteur->role)

        case "listActeur": $ctlrPersonne->listActeur(); break; // Affiche la vue Liste Acteur
        case "listReal": $ctlrPersonne->listRealisateur(); break; // Affiche la vue Liste Réalisateur
        case "detPersonne": $ctlrPersonne->detPersonne($id); break; // Affiche la vue detail Personne
        case "gestionPersonne": $ctlrPersonne->gestionPersonne(); break; // Affiche la gestion d'un ajout de personne (formulaire)
        case "addPersonne": $ctlrPersonne->addPersonne(); break; // Effectue l'ajout d'une personne (traitement)
        case "editerPersonne": $ctlrPersonne->editerPersonne($id); break; // Affiche la gestion d'un edit de personne (formulaire)
        case "editPersonne": $ctlrPersonne->editPersonne($id); break; // Effectue l'édit d'une personne (traitement)
        case "delPersonne": $ctlrPersonne->delPersonne($id); break; // Effectue la suppression d'une personne (traitement)
        
        case "listRole": $ctlrRole->listRole(); break; // Affiche la vue Liste Role
        case "detRole": $ctlrRole->detRole($id); break; // Affiche la vue Detail role
        case "gestionRole" : $ctlrRole->gestionRole(); break; // Affiche la vue gestion Role
        case "addRole" : $ctlrRole->addRole(); break; // Permet l'ajout d'un Role
        case "editerRole" : $ctlrRole->editerRole($id); break;
        case "editRole" : $ctlrRole->editRole($id); break;
        case "delRole" : $ctlrRole->delRole($id); break;

        case "listCategorie": $ctlrCategorie->listCategorie(); break; // Affiche la vue Liste Catégorie
        case "detCategorie": $ctlrCategorie->detCategorie($id); break; // affiche la vue Detail Catégorie
        case "gestionCategorie" : $ctlrCategorie->gestionCategorie(); break; // Affiche la vue gestion Categorie
        case "addCategorie": $ctlrCategorie->addCategorie(); break; // Permet l'ajout d'une catégorie
        case "editerCategorie": $ctlrCategorie->editerCategorie($id); break;
        case "editCategorie": $ctlrCategorie->editCategorie($id); break;
        case "delCategorie": $ctlrCategorie->delCategorie($id); break;

        case "gestionCasting": $ctlrCasting->gestionCasting(); break; // Affiche la gestion d'un ajout de casting (formulaire)
        case "addCasting": $ctlrCasting->addCasting(); break; // Effectue l'ajout d'un casting (traitement)
        


    }
}
else {  // Sinon, j'affiche la page d'acceuil
    $ctlrCinema->afficheTitrefilm();
}

?>
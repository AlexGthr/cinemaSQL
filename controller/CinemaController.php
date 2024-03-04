<?php 


namespace Controller;
use Model\Connect;
use Model\Service;

class CinemaController {


     public function afficheTitrefilm() {

        $pdo = Connect::seConnecter();

        // Requete pour l'affichage des films sur l'acceuil
        $requete = $pdo->query("
                SELECT film.affiche, film.titre, film.id_film
                FROM film
                ORDER BY film.dateDeSortie DESC
                LIMIT 2
                ");

        // Requete pour l'affichage des acteurs sur l'acceuil
        $requeteA = $pdo->query("
        SELECT  personne.photo,
                CONCAT(personne.prenom, ' ',personne.nom) AS acteur,
                personne.id_personne,
                COUNT(joue.id_role) AS nbRole
        FROM acteur
        INNER JOIN personne ON acteur.id_personne = personne.id_personne
        INNER JOIN joue ON acteur.id_acteur = joue.id_acteur
        GROUP BY acteur.id_acteur
        ORDER BY nbRole DESC
        LIMIT 3
                ");

        // Requete pour l'affichage des réalisateurs sur l'acceuil
        $requeteR = $pdo->query("
        SELECT 
                personne.photo, 
                CONCAT(personne.prenom, ' ',personne.nom) AS realisateur,
                personne.id_personne,
                COUNT(film.id_realisateur) AS nbFilm
        FROM realisateur
        INNER JOIN personne ON realisateur.id_personne = personne.id_personne
        INNER JOIN film ON realisateur.id_realisateur = film.id_realisateur
	GROUP BY realisateur.id_realisateur
	ORDER BY nbFilm DESC
	LIMIT 3
                ");
        require "view/acceuil.php";
     }

     public function gestion() {
        require "view/formulaire/gestion.php";
     }

     public function research() {
        $pdo = Connect::seConnecter();

        if (isset($_GET['action'])) {
                $research = filter_input(INPUT_POST, "research", FILTER_SANITIZE_SPECIAL_CHARS);

            $requeteFilm = $pdo->prepare("
            SELECT
                film.id_film,
                film.titre
            FROM film
            WHERE film.titre LIKE :research
            "); 
            
            $requeteFilm->execute(["research" => '%' . $research . '%']);

            $requetePersonne = $pdo->prepare("
            SELECT
                CONCAT(personne.prenom, ' ',personne.nom) AS nomPersonne,
                id_personne
            FROM personne
            WHERE personne.prenom LIKE :research
            OR personne.nom LIKE :research
            ");

            $requetePersonne->execute(["research" => '%' . $research . '%']);

            $requeteRole = $pdo->prepare("
            SELECT
                nom,
                id_role
            FROM role
            WHERE nom LIKE :research
            ");

            $requeteRole->execute(["research" => '%' . $research . '%']);

            $requeteCategorie = $pdo->prepare("
            SELECT
                type,
                id_categorie
            FROM categorie
            WHERE type LIKE :research
            ");

            $requeteCategorie->execute(["research" => '%' . $research . '%']);

            require "view/research.php";
        }
     }
   
}
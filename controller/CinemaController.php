<?php 

namespace Controller;
use Model\Connect;

class CinemaController {

    /**
     * Affiche et titre films
     */

     public function afficheTitrefilm() {

        $pdo = Connect::seConnecter();

        // Requete pour l'affichage des films sur l'acceuil
        $requete = $pdo->query("
                SELECT film.affiche, film.titre, film.id_film
                FROM film
                LIMIT 2
                ");

        // Requete pour l'affichage des acteurs sur l'acceuil
        $requeteA = $pdo->query("
        SELECT  personne.photo,
                CONCAT(personne.prenom, ' ',personne.nom) AS acteur,
                personne.id_personne
        FROM acteur
        INNER JOIN personne ON acteur.id_personne = personne.id_personne
        WHERE personne.id_personne = 21 
        OR personne.id_personne = 22 
        OR personne.id_personne = 27
                ");

        // Requete pour l'affichage des réalisateurs sur l'acceuil
        $requeteR = $pdo->query("
        SELECT 
                personne.photo, 
                CONCAT(personne.prenom, ' ',personne.nom) AS realisateur,
                personne.id_personne
        FROM realisateur
        INNER JOIN personne ON realisateur.id_personne = personne.id_personne
        WHERE personne.id_personne = 2
        OR personne.id_personne = 8
        OR personne.id_personne = 14
                ");
        require "view/acceuil.php";
     }

     public function listFilm() {

        $pdo = Connect::seConnecter();

        // Requete pour afficher la liste des films
        $requete = $pdo->query("
        SELECT 
            film.id_film,
            film.affiche,
            film.titre,
            DATE_FORMAT(film.dateDeSortie, '%d/%m/%Y') AS dateDeSortie,
            TIME_FORMAT(SEC_TO_TIME(film.duree*60), '%Hh%i') AS dureeFilm,
            film.note
        FROM film
        ");
        require "view/film/listeFilm.php";
     }

     public function detFilm($id) {
        
        $pdo = Connect::seConnecter();

        // Requete pour afficher le detail complet d'UN seul film
        $requete = $pdo->prepare("
        SELECT
                film.id_film,
                personne.id_personne AS idPersonne, 
                film.affiche,
                film.titre,
                DATE_FORMAT(film.dateDeSortie, '%d/%m/%Y') AS dateDeSortie,
                TIME_FORMAT(SEC_TO_TIME(film.duree*60), '%Hh%i') AS dureeFilm,
                film.note,
                CONCAT(personne.prenom, ' ',personne.nom) AS leRealisateur
        FROM film
        INNER JOIN realisateur ON film.id_realisateur = realisateur.id_realisateur
        INNER JOIN personne ON realisateur.id_personne = personne.id_personne
         WHERE film.id_film = :id
        ");
        $requete->execute(["id" => $id]);

        // Requete pour afficher TOUT les roles et acteurs du film
        $requeteActeurRole = $pdo->prepare("
        SELECT 
                role.id_role AS idRole,
                personne.id_personne AS idPersonne,
	        role.nom AS nomRole,
	        CONCAT(personne.prenom, ' ',personne.nom) AS nomActeurs
        FROM joue
        INNER JOIN film ON joue.id_film = film.id_film
        INNER JOIN acteur ON joue.id_acteur = acteur.id_acteur
        INNER JOIN personne ON acteur.id_personne = personne.id_personne
        INNER JOIN role ON joue.id_role = role.id_role
        WHERE film.id_film = :id
        ");
        $requeteActeurRole->execute(["id" => $id]);
        require "view/film/detailFilm.php";
     }

     public function listActeur() {

        $pdo = Connect::seConnecter();

        // Requete pour afficher la liste de tout les acteurs
        $requete = $pdo->query("
        SELECT  personne.id_personne,
                personne.photo,
                CONCAT(personne.prenom, ' ',personne.nom) AS acteur,
                DATE_FORMAT(personne.dateDeNaissance, '%d/%m/%Y') as dateDeNaissance
        FROM acteur
        INNER JOIN personne ON acteur.id_personne = personne.id_personne
        ORDER BY personne.sexe
        ");

        require "view/acteur/listActeur.php";
     }

     public function detPersonne($id) {

        $pdo = Connect::seConnecter();

        // Requete pour afficher les infos d'une seul personne
        $requete = $pdo->prepare("
        SELECT
                personne.photo,
                CONCAT(personne.nom, ' ',personne.prenom) AS laPersonne,
                personne.sexe,
                DATE_FORMAT(personne.dateDeNaissance, '%d/%m/%Y') AS dateNaissance,
                acteur.id_acteur AS idActeur,
                realisateur.id_realisateur AS idRealisateur
        FROM personne
        LEFT JOIN acteur ON personne.id_personne = acteur.id_personne
        LEFT JOIN realisateur ON personne.id_personne = realisateur.id_personne
        WHERE personne.id_personne = :id
        ");
        $requete->execute(["id" => $id]);
        
        // Requete pour afficher la filmographie et le role d'un acteur
        $requeteFilmoActeur = $pdo->prepare("
        SELECT
        film.id_film,  
        film.titre,
        role.id_role AS idRole, 
        role.nom AS role,
        DATE_FORMAT(film.dateDeSortie, '%Y') AS dateSortie
        FROM joue
        INNER JOIN acteur ON joue.id_acteur = acteur.id_acteur
        INNER JOIN personne ON acteur.id_personne = personne.id_personne
        INNER JOIN film ON joue.id_film = film.id_film
        INNER JOIN role ON joue.id_role = role.id_role
        WHERE personne.id_personne = :id
        ORDER BY dateSortie
        ");
        $requeteFilmoActeur->execute(["id" => $id]);

        $requeteFilmoRealisateur = $pdo->prepare("
        SELECT
                film.id_film,
                film.titre,
                YEAR(film.dateDeSortie) AS date
        FROM film
        INNER JOIN realisateur ON film.id_realisateur = realisateur.id_realisateur
        INNER JOIN personne ON realisateur.id_realisateur = personne.id_personne
        WHERE realisateur.id_personne = :id
        ");
        $requeteFilmoRealisateur->execute(["id" => $id]);

        require "view/personne/detailPersonne.php";
}
}
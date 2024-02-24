<?php 

namespace Controller;
use Model\Connect;

class CinemaController {

    /**
     * Affiche et titre films
     */

     public function afficheTitrefilm() {

        $pdo = Connect::seConnecter();

        $requete = $pdo->query("
                SELECT film.affiche, film.titre, film.id_film
                FROM film
                LIMIT 2
                ");

        $requeteA = $pdo->query("
        SELECT  personne.photo,
                CONCAT(personne.prenom, ' ',personne.nom) AS acteur
        FROM acteur
        INNER JOIN personne ON acteur.id_personne = personne.id_personne
        WHERE personne.id_personne = 21 
        OR personne.id_personne = 22 
        OR personne.id_personne = 27
                ");

        $requeteR = $pdo->query("
        SELECT personne.photo, CONCAT(personne.prenom, ' ',personne.nom) AS realisateur
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

        $requete = $pdo->query("
        SELECT 
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

        $requete = $pdo->prepare("
            SELECT 
                film.affiche,
                film.titre,
                DATE_FORMAT(film.dateDeSortie, '%d/%m/%Y') AS dateDeSortie,
                TIME_FORMAT(SEC_TO_TIME(film.duree*60), '%Hh%i') AS dureeFilm,
                film.note
            FROM film
            WHERE film.id_film = :id
        ");
        $requete->execute(["id" => $id]);
        require "view/film/detailFilm.php";
     }
}
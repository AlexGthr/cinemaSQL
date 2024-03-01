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

     public function gestion() {
        require "view/formulaire/gestion.php";
     }
   
}
<?php 


namespace Controller;
use Model\Connect;
use Model\Service;

class CastingController { 


    public function gestionCasting() {  // Récupère les requêtes SQL pour le formulaire d'un casting

        $pdo = Connect::seConnecter();

        // Je récupère l'id d'un film et son titre
        $requeteFilm = $pdo->query("
        SELECT 
                film.id_film AS idFilm,
                film.titre AS titre
        FROM film
        ORDER BY idFilm DESC
        ");

        // Je récupère l'id d'un acteur et son nom/prénom
        $requeteActeur = $pdo->query("
        SELECT 
                acteur.id_acteur AS idActeur,
                CONCAT(personne.prenom, ' ',personne.nom) AS lesActeurs
        FROM acteur
        INNER JOIN personne ON acteur.id_personne = personne.id_personne
        ORDER BY idActeur DESC
        ");

        // Je récupère l'id d'un role et son nom
        $requeteRole = $pdo->query("
        SELECT
                role.id_role AS idRole,
                role.nom AS nom
        FROM role
        ORDER BY idRole DESC
        ");

        require "view/formulaire/gestionCasting.php";
    }

    public function addCasting() { // Permet l'ajout d'un casting

        session_start();

        $pdo = Connect::seConnecter();

        // Je récupère l'action
        if(isset($_GET['action'])) { 

                // Puis je filtre mes résultats
                $film = filter_input(INPUT_POST, "film", FILTER_VALIDATE_INT);
                $acteur = filter_input(INPUT_POST, "acteur", FILTER_VALIDATE_INT);
                $role = filter_input(INPUT_POST, "role", FILTER_VALIDATE_INT);

                // Je crée une/des conditions
                $condition = is_numeric($film) && is_numeric($acteur) && is_numeric($role);

                // En fonctions de l'erreur, j'affiche un message personnalisé
                if(!is_numeric($film)) {

                        $_SESSION['message'] = "<p>Oups. Problème avec le film.</p>";
                        header("Location:index.php?action=gestionCasting");

                } else if (!is_numeric($acteur)) {

                        $_SESSION['message'] = "<p>Oups. Problème avec l'acteur.</p>";
                        header("Location:index.php?action=gestionCasting"); 

                } else if (!is_numeric($role)) {

                        $_SESSION['message'] = "<p>Oups. Problème avec le role.</p>";
                        header("Location:index.php?action=gestionCasting");

                } else if ($condition) { // Si tout vas bien, je crée mon casting

                        $requete = $pdo->prepare("
                        INSERT INTO joue
                           (id_film, id_acteur, id_role)
                        VALUES
                           (:id_film, :id_acteur, :id_role)
                        ");

                        $requete->execute([
                                'id_film' => $film,
                                'id_acteur' => $acteur,
                                'id_role' => $role
                        ]);

                        $_SESSION['message'] = "<p>Casting bien ajouté !</p>";
                        header("Location:index.php?action=gestionCasting");
                }
        }

        
    }
}
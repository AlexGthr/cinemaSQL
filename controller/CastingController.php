<?php 


namespace Controller;
use Model\Connect;
use Model\Service;

class CastingController { 


    public function gestionCasting() {

        $pdo = Connect::seConnecter();

        $requeteFilm = $pdo->query("
        SELECT 
                film.id_film AS idFilm,
                film.titre AS titre
        FROM film
        ORDER BY idFilm DESC
        ");

        $requeteActeur = $pdo->query("
        SELECT 
                acteur.id_acteur AS idActeur,
                CONCAT(personne.prenom, ' ',personne.nom) AS lesActeurs
        FROM acteur
        INNER JOIN personne ON acteur.id_personne = personne.id_personne
        ORDER BY idActeur DESC
        ");

        $requeteRole = $pdo->query("
        SELECT
                role.id_role AS idRole,
                role.nom AS nom
        FROM role
        ORDER BY idRole DESC
        ");

        require "view/formulaire/gestionCasting.php";
    }

    public function addCasting() {

        session_start();

        $pdo = Connect::seConnecter();

        if(isset($_GET['action'])) {
                $film = filter_input(INPUT_POST, "film", FILTER_VALIDATE_INT);
                $acteur = filter_input(INPUT_POST, "acteur", FILTER_VALIDATE_INT);
                $role = filter_input(INPUT_POST, "role", FILTER_VALIDATE_INT);

                $condition = is_numeric($film) && is_numeric($acteur) && is_numeric($role);

                if(!is_numeric($film)) {
                        $_SESSION['message'] = "<p>Oups. Problème avec le film.</p>";
                        header("Location:index.php?action=gestionCasting");
                } else if (!is_numeric($acteur)) {
                        $_SESSION['message'] = "<p>Oups. Problème avec l'acteur.</p>";
                        header("Location:index.php?action=gestionCasting");   
                } else if (!is_numeric($role)) {
                        $_SESSION['message'] = "<p>Oups. Problème avec le role.</p>";
                        header("Location:index.php?action=gestionCasting");
                } else if ($condition) {
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
<?php 


namespace Controller;
use Model\Connect;
use Model\Service;

class CategorieController {

    public function listCategorie() {
        
        $pdo = Connect::seConnecter();

        $requete = $pdo->query("        
        SELECT *
        FROM categorie
        ");

        require "view/categorie/listeCategorie.php";
    }

    public function detCategorie($id) {
        
        if(!Service::exist("categorie", $id)) {
                header("Location:index.php?action=listCategorie");
                exit;
        } else {

        $pdo = Connect::seConnecter();

        $requete = $pdo->prepare("
        SELECT 
                categorie.id_categorie AS idCategorie,
                categorie.type AS typeCategorie
        FROM categorie
        WHERE categorie.id_categorie = :id
        ");
        $requete->execute(["id" => $id]);

        $requeteFilmCategorie = $pdo->prepare("
        SELECT
                film.id_film AS idFilm,
                film.titre,
                categorie.type AS typeCategorie,
                DATE_FORMAT(film.dateDeSortie, '%Y') AS dateDeSortie
        FROM categoriser
        INNER JOIN film ON categoriser.id_film = film.id_film
        INNER JOIN categorie ON categoriser.id_categorie = categorie.id_categorie
        WHERE categorie.id_categorie = :id
        ORDER BY dateDeSortie
        ");
        $requeteFilmCategorie->execute(["id" => $id]);

        require "view/categorie/detailCategorie.php";
        }
    }

    public function gestionCategorie() {
        require "view/formulaire/gestionCategorie.php";
    }

    public function addCategorie() {

        session_start();

        $pdo = Connect::seConnecter();

        if(isset($_GET['action'])) {
                $categorie = filter_input(INPUT_POST, "categorie", FILTER_SANITIZE_SPECIAL_CHARS);

                if(!empty($categorie) && strlen($categorie) <= 20 && preg_match("/^[A-Za-z '-]+$/", $categorie)) {

                        $requete = $pdo->prepare("
                        INSERT INTO categorie
                           (type)
                        VALUES
                           (:nom)
                        ON DUPLICATE KEY UPDATE type = :nom
                        ");

                        $requete->execute(['nom' => $categorie]);

                        $_SESSION['message'] = "<p> Votre catégorie à bien été enrengistré ! </p>";
                        header("Location:index.php?action=gestionCategorie");
                }
                else {
                        $_SESSION['message'] = "<p>Oups. Votre catégorie n'as pas pu être enrengistré. Verifier vos informations.</p>";
                        header("Location:index.php?action=gestionCategorie");
                }
        }
    }
 }
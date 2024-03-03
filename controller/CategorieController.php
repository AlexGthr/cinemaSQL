<?php 


namespace Controller;
use Model\Connect;
use Model\Service;

class CategorieController {

    public function listCategorie() { // Function qui gère l'affichage de la liste de mes catégories
        
        $pdo = Connect::seConnecter();

        // Je récupère tout mes éléments dans ma table catégories (id et type)
        $requete = $pdo->query("        
        SELECT *
        FROM categorie
        ");

        require "view/categorie/listeCategorie.php";
    }

    public function detCategorie($id) {  // Requête permettant d'afficher le detail d'une catégorie
        
        // Function si l'ID existe ou pas, permettant de gerer le cas d'un id inexistant via l'url
        if(!Service::exist("categorie", $id)) {
                header("Location:index.php?action=listCategorie");
                exit;
        } else {

        $pdo = Connect::seConnecter();

        // Je récupère mon id catégorie ainsi que mon type de catégorie par rapport à mon ID
        $requete = $pdo->prepare("
        SELECT 
                categorie.id_categorie AS idCategorie,
                categorie.type AS typeCategorie
        FROM categorie
        WHERE categorie.id_categorie = :id
        ");
        $requete->execute(["id" => $id]);

        // Je récupère l'id d'un film, son titre, le nom des categories et la dae de sortie d'un film par rapport à son ID
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

    public function gestionCategorie() { // Formulaire pour l'ajout de catégorie (affiche uniquement la vue)
        require "view/formulaire/gestionCategorie.php";
    }

    public function addCategorie() { // Traitement pour l'ajout d'une catégorie

        session_start();

        $pdo = Connect::seConnecter();

        // Je vérifie qu'il existe une action
        if(isset($_GET['action'])) {

                // Je filtre mon resultat
                $categorie = filter_input(INPUT_POST, "categorie", FILTER_SANITIZE_SPECIAL_CHARS);

                // J'applique des conditions puis j'ajoute ma catégorie
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
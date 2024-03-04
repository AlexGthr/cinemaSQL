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
        ORDER BY type
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

        // Je récupère l'id d'un film, son titre, le nom des categories et la date de sortie d'un film par rapport à son ID
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

    public function editerCategorie($id) {
        $pdo = Connect::seConnecter();

        if(!Service::exist("categorie", $id)) {
                header("Location:index.php?action=listRole");
                exit;
        } else { 

                $requete = $pdo->prepare("
                SELECT
                        categorie.id_categorie,
                        categorie.type
                FROM categorie
                WHERE id_categorie = :id
                ");

                $requete->execute(["id" => $id]);
                require "view/categorie/editCategorie.php";
        }
    }

    public function editCategorie($id) {
        
        session_start();

        $pdo = Connect::seConnecter();

        if(isset($_GET['action'])) {
                $categorie = filter_input(INPUT_POST, "categorie", FILTER_SANITIZE_SPECIAL_CHARS);

                if(!empty($categorie) && strlen($categorie) <= 20 && preg_match("/^[A-Za-z '-]+$/", $categorie)) {

                        $requete = $pdo->prepare("
                        UPDATE categorie
                        SET 
                                type = :categorie
                        WHERE id_categorie = :id
                        ");

                        $requete->execute([
                                'categorie' => $categorie,
                                'id' => $id]);

                        $_SESSION['message'] = "<p> Votre categorie à bien été enrengistré ! </p>
                                                <a href='index.php?action=detCategorie&id=". $id . "'> Accès à la catégorie </a>";
                        header("Location:index.php?action=editerCategorie&id=$id");
                }
                else {
                        $_SESSION['message'] = "<p>Oups. Votre categorie n'as pas pu être enrengistré. Verifier vos informations.</p>";
                        header("Location:index.php?action=editerCategorie&id=$id");
                }
        }
    }

    public function delCategorie($id) {

        $pdo = Connect::seConnecter();

        if(!Service::exist("categorie", $id)) {
                header("Location:index.php?action=listCategorie");
                exit;
        } else { 

                $requeteCategoriser = $pdo->prepare("
                DELETE FROM categoriser
                WHERE id_categorie = :id
                ");

                $requeteCategoriser->execute(["id" => $id]);

                $requete = $pdo->prepare("
                DELETE FROM categorie
                WHERE id_categorie = :id
                ");
                $requete->execute(["id" => $id]);

                header("Location:index.php");
        }
    }
 }
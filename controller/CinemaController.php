<?php 


namespace Controller;
use Model\Connect;
use Model\Service;

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

    public function listRole() {

        $pdo = Connect::seConnecter();

        $requete = $pdo->query("
        SELECT 
                role.id_role AS idRole,
                personne.id_personne AS idPersonne,
	        role.nom AS nomRole,
	        CONCAT(personne.prenom, ' ',personne.nom) AS nomActeurs
        FROM joue
        LEFT JOIN acteur ON joue.id_acteur = acteur.id_acteur
        LEFT JOIN personne ON acteur.id_personne = personne.id_personne
        LEFT JOIN role ON joue.id_role = role.id_role
        ");

        require "view/role/listRole.php";

        }

     public function detRole($id) {

        $pdo = Connect::seConnecter();

        if(!Service::exist("role", $id)) {
                header("Location:index.php?action=listRole");
                exit;
        } else {

        $requete = $pdo->prepare("
        SELECT 
                film.affiche,
                film.titre AS titreFilm,
                film.id_film AS idFilm,
                role.id_role AS idRole,
                personne.id_personne AS idPersonne,
                role.nom AS nomRole,
                CONCAT(personne.prenom, ' ',personne.nom) AS nomActeurs
        FROM joue
        INNER JOIN film ON joue.id_film = film.id_film
        INNER JOIN acteur ON joue.id_acteur = acteur.id_acteur
        INNER JOIN personne ON acteur.id_personne = personne.id_personne
        INNER JOIN role ON joue.id_role = role.id_role
        WHERE joue.id_role = :id
        ");

        $requete->execute(["id" => $id]);

        require "view/role/detailRole.php";

        }
 }


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

        public function gestion() {
                require "view/formulaire/gestion.php";
        }

        public function gestionRole() {
                require "view/formulaire/gestionRole.php";
        }

        public function gestionCategorie() {
                require "view/formulaire/gestionCategorie.php";
        }

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

        public function addRole() {

        session_start();

        $pdo = Connect::seConnecter();

        if(isset($_GET['action'])) {
                $role = filter_input(INPUT_POST, "role", FILTER_SANITIZE_SPECIAL_CHARS);

                if(!empty($role) && strlen($role) <= 20 && preg_match("/^[A-Za-z '-]+$/", $role)) {

                        $requete = $pdo->prepare("
                        INSERT INTO role
                           (nom)
                        VALUES
                           (:nom)
                        ");

                        $requete->execute(['nom' => $role]);

                        $_SESSION['message'] = "<p> Votre role à bien été enrengistré ! </p>";
                        header("Location:index.php?action=gestionRole");
                }
                else {
                        $_SESSION['message'] = "<p>Oups. Votre role n'as pas pu être enrengistré. Verifier vos informations.</p>";
                        header("Location:index.php?action=gestionRole");
                }
        }
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

        

        public function addFilm() {

                session_start();
                $pdo = Connect::seConnecter();

                // Je vérifie que j'ai bien recu via le formulaire l'action :

                if(isset($_GET['action']) && isset($_FILES['file'])) {

        // Filtrage des données
                        $titre = ucfirst(filter_input(INPUT_POST, "titre", FILTER_SANITIZE_SPECIAL_CHARS));
                        $dateDeSortie = filter_input(INPUT_POST, "dateSortie", FILTER_SANITIZE_SPECIAL_CHARS);
                        $duree = filter_input(INPUT_POST, "duree", FILTER_VALIDATE_INT);
                        $synopsys = filter_input(INPUT_POST, "synopsys", FILTER_SANITIZE_SPECIAL_CHARS);
                        $note = filter_input(INPUT_POST, "note", FILTER_VALIDATE_INT);
                        $realisateur = filter_input(INPUT_POST, "realisateur", FILTER_VALIDATE_INT);

                        // Je récupère l'image reçu
                        $tmpName = $_FILES['file']['tmp_name'];
                        $nameImg = $_FILES['file']['name'];
                        $size = $_FILES['file']['size'];
                        $erreur = $_FILES['file']['error'];

                        // Je sépare le nom de l'extension
                        $tabExtension = explode('.', $nameImg);
                        // Je met l'extension en minuscule
                        $extension = strtolower(end($tabExtension));
                        // Je crée un tableau pour accepter UNIQUEMENT ce genre d'extension
                        $extensions = ['jpg', 'png', 'jpeg'];
                        // Et je crée une condition pour la taille MAX d'une image (1 MO ici)
                        $maxSize = 1000000;

                         // Je crée deux condition pour ne pas faire un IF à rallonge
                        $condition1 = !empty($titre) && strlen($titre) <= 20 && preg_match("/^[A-Za-z0-9 '-]+$/", $titre);
                        $condition2 = is_numeric($duree) && is_numeric($note) && is_numeric($realisateur);

                        if (!$condition1) {
                                $_SESSION['message'] .= "<p> Problème avec le titre. Merci de changer le titre </p>";
                                header("Location:index.php?action=gestionFilm");
                        } elseif (!is_numeric($duree) && $duree < 0) {
                                $_SESSION['message'] .= "<p> La durée n'est pas correct. </p>";
                                header("Location:index.php?action=gestionFilm");
                        } elseif (!is_numeric($note) && $note > 5 && $note < 0) {
                                $_SESSION['message'] .= "<p> La note n'est pas correct. </p>";
                                header("Location:index.php?action=gestionFilm");
                        } elseif (!is_numeric($realisateur)) {
                                $_SESSION['message'] .= "<p> Problème avec le réalisateur. </p>";
                                header("Location:index.php?action=gestionFilm");
                        }  elseif ($condition1 && $condition2 && $size <= $maxSize && $erreur == 0) {
                                
                                $uniqueName = uniqid('', true);
                                //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
                                $file = $uniqueName.".".$extension;
                                move_uploaded_file($tmpName, './public/img/afficheFilm/'.$file);

                                // Je crée le chemin de l'image pour la BDD
                                $cheminfile = "public/img/afficheFilm/" . $file;

                                $requete = $pdo->prepare("
                                INSERT INTO film
                                        (titre, dateDeSortie, duree, synopsis, note, affiche, id_realisateur)
                                VALUES
                                        (:titre, :dateDeSortie, :duree, :synopsis, :note, :affiche, :id_realisateur)
                                ");
        
                                $requete->execute([
                                        'titre' => $titre,
                                        'dateDeSortie' => $dateDeSortie, 
                                        'duree' => $duree, 
                                        'synopsis' => $synopsys, 
                                        'note' => $note,
                                        'affiche' => $cheminfile,
                                        'id_realisateur' => $realisateur]);

                                $lastFilm = $pdo->lastInsertId();

                                foreach ($_POST['categorie'] as $categorieFilm) {

                                        $categorieFilm = filter_var($categorieFilm, FILTER_VALIDATE_INT);

                                        if ($categorieFilm) {

                                                $requeteCategorie = $pdo->prepare("
                                                INSERT INTO categoriser
                                                        (id_film, id_categorie)
                                                VALUES
                                                        (:id_film, :id_categorie)
                                                ");
                
                                                $requeteCategorie->execute([
                                                        'id_film' => $lastFilm,
                                                        'id_categorie' => $categorieFilm]);
                                        }
                                        
                                }


                                $_SESSION['message'] .= "<p> Votre film est bien enrengistré </p>";
                                header("Location:index.php?action=gestionFilm");
                        }
                        
                        
                }
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
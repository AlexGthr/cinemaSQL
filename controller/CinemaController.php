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
                film.synopsis,
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

        $requeteCategorie = $pdo->prepare("
        SELECT
                categorie.id_categorie as idCategorie,
                categorie.`type` AS typeCategorie
        FROM categoriser
        LEFT JOIN film ON categoriser.id_film = film.id_film
        LEFT JOIN categorie ON categoriser.id_categorie = categorie.id_categorie
        WHERE film.id_film = :id
        ");

        $requeteCategorie->execute(["id" => $id]);

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

     public function listRealisateur() {

        $pdo = Connect::seConnecter();

        $requete = $pdo->query("
        SELECT  personne.id_personne,
                personne.photo,
                CONCAT(personne.prenom, ' ',personne.nom) AS leRealisateur,
                DATE_FORMAT(personne.dateDeNaissance, '%d/%m/%Y') as dateDeNaissance
        FROM realisateur
        INNER JOIN personne ON realisateur.id_personne = personne.id_personne
        ORDER BY personne.sexe
        ");
        
        require "view/realisateur/listRealisateur.php";
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

        public function listCategorie() {
        
        $pdo = Connect::seConnecter();

        $requete = $pdo->query("        
        SELECT *
        FROM categorie
        ");

        require "view/categorie/listeCategorie.php";
        }

        public function detCategorie($id) {

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

        public function gestion() {
                require "view/formulaire/gestion.php";
        }

        public function gestionRole() {
                require "view/formulaire/gestionRole.php";
        }

        public function gestionCategorie() {
                require "view/formulaire/gestionCategorie.php";
        }

        public function gestionPersonne() {
                require "view/formulaire/gestionPersonne.php";
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

        public function addPersonne() {

                session_start();
                $pdo = Connect::seConnecter();

                // Je vérifie que j'ai bien recu via le formulaire une action, un type et un file.
                if(isset($_GET['action']) && isset($_GET['type']) && isset($_FILES['file'])) {

                        // Je filtre toutes les données reçu
                        $lastname = filter_input(INPUT_POST, "last", FILTER_SANITIZE_SPECIAL_CHARS);
                        $lastname = ucfirst($lastname);
                        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
                        $name = ucfirst($name);
                        $genre = filter_input(INPUT_POST, "genre", FILTER_SANITIZE_SPECIAL_CHARS);
                        $dateNaissance = filter_input(INPUT_POST, "dateNaissance", FILTER_SANITIZE_SPECIAL_CHARS);
                        $metier = filter_input(INPUT_POST, "metier", FILTER_SANITIZE_SPECIAL_CHARS);


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
                        $condition1 = !empty($lastname) && strlen($lastname) <= 20 && preg_match("/^[A-Za-z '-]+$/", $lastname);
                        $condition2 = !empty($name) && strlen($name) <= 20 && preg_match("/^[A-Za-z '-]+$/", $name);


                        if(!$condition1) { // Si le nom à un problème
                                $_SESSION['message'] .= "<p> Problème avec le nom. Merci de changer le nom </p>";
                                header("Location:index.php?action=gestionPersonne");

                        } elseif (!$condition2) { // si le prénom à un problème
                                $_SESSION['message'] .= "<p> Problème avec le prénom. Merci de changer le prénom </p>";
                                header("Location:index.php?action=gestionPersonne");

                        } elseif ($genre !== "M" && $genre !== "F") { // si le genre n'est pas bon
                                $_SESSION['message'] .= "<p> Problème avec le genre. Merci de changer le genre. </p>";
                                header("Location:index.php?action=gestionPersonne");

                        } elseif ($size >= $maxSize) { // Si la taille de l'image n'est pas correct
                                $_SESSION['message'] .= "<p> Problème avec la taille de votre image. Merci de changer votre image. (taille max : 1mo)</p>";
                                header("Location:index.php?action=gestionPersonne");

                        } elseif ($erreur > 0) { // Si l'image à une erreur
                                $_SESSION['message'] .= "<p> Erreur upload file. </p>";
                                header("Location:index.php?action=gestionPersonne");
                        }
                           // Si tout vas bien, je crée une condition total puis j'ajoute la personne dans la BDD
                        elseif ($condition1 && $condition2 && ($genre == "M" || $genre == "F") && $dateNaissance && $metier && $size <= $maxSize && $erreur == 0) {

                                // Je crée une nom unique à l'image
                                $uniqueName = uniqid('', true);
                                //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
                                $file = $uniqueName.".".$extension;
                                move_uploaded_file($tmpName, './public/img/personne/'.$file);

                                // Je crée le chemin de l'image pour la BDD
                                $cheminfile = "public/img/personne/" . $file;

                                $requete = $pdo->prepare("
                                INSERT INTO personne
                                   (nom, prenom, sexe, dateDeNaissance, photo)
                                VALUES
                                   (:nom, :prenom, :sexe, :dateDeNaissance, :photo)
                                ");
        
                                $requete->execute(['nom' => $lastname, 'prenom' => $name, 'sexe' => $genre, 'dateDeNaissance' => $dateNaissance, 'photo' => $cheminfile]);

                        }

                        // Si la personne est un acteur, alors je la rajoute dans la table ACTEUR
                        if(isset($_GET['type']) && $_GET['type'] == "acteur") {

                                $idPersonne = $pdo->lastInsertId();

                                $requeteActeur = $pdo->prepare("
                                INSERT INTO acteur
                                        (id_personne)
                                VALUES
                                        (:id_personne)
                                ");
                                $requeteActeur->execute(['id_personne' => $idPersonne]);

                                $_SESSION['message'] .= "<p> Votre ACTEUR est bien enrengistré </p>";
                                header("Location:index.php?action=gestionPersonne");

                                // Si la personne est un réalisateur, alors je la rajoute dans la table REALISATEUR
                        } elseif (isset($_GET['type']) && $_GET['type'] == "realisateur") {

                                $idPersonne = $pdo->lastInsertId();

                                $requeteRealisateur = $pdo->prepare("
                                INSERT INTO realisateur
                                        (id_personne)
                                VALUES
                                        (:id_personne)
                                ");
                                $requeteRealisateur->execute(['id_personne' => $idPersonne]);

                                $_SESSION['message'] .= "<p> Votre REALISATEUR est bien enrengistré </p>";
                                header("Location:index.php?action=gestionPersonne");

                                // Si elle à les deux métier, alors je l'ajoute dans les deux table
                        } elseif (isset($_GET['type']) && $_GET['type'] == "lesdeux") {

                                $idPersonne = $pdo->lastInsertId();

                                // Insérer dans la table acteur
                                $requeteActeur = $pdo->prepare("
                                INSERT INTO acteur (id_personne)
                                VALUES (:id_personne)
                                ");
                                $requeteActeur->execute(['id_personne' => $idPersonne]);

                                // Insérer dans la table réalisateur
                                $requeteRealisateur = $pdo->prepare("
                                INSERT INTO realisateur (id_personne)
                                VALUES (:id_personne)
                                ");
                                $requeteRealisateur->execute(['id_personne' => $idPersonne]);


                                $_SESSION['message'] .= "<p> Votre REALISATEUR+ACTEUR est bien enrengistré </p>";
                                header("Location:index.php?action=gestionPersonne");     
                        }
                }
        }
}
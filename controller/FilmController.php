<?php 


namespace Controller;
use Model\Connect;
use Model\Service;

class FilmController { 

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
        ORDER BY DATE_FORMAT(dateDeSortie, '%Y/%m/%d') DESC
        ");
        require "view/film/listeFilm.php";
    }


    public function detFilm($id) {
        
        $pdo = Connect::seConnecter();

        if(!Service::exist("film", $id)) {
                header("Location:index.php?action=listFilm");
                exit;
        } else {

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
    }


    public function gestionFilm() {

        $pdo = Connect::seConnecter();

        $requeteReal = $pdo->query("
        SELECT
                CONCAT(personne.prenom, ' ',personne.nom) AS lesRealisateur, 
                realisateur.id_realisateur AS idRealisateur
        FROM realisateur
        INNER JOIN personne ON realisateur.id_personne = personne.id_personne
        ");

        $requeteCategorie = $pdo->query("
        SELECT 
                categorie.id_categorie AS idCategorie,
                categorie.`type` AS typeCategorie
        FROM categorie
        ");

        require "view/formulaire/gestionFilm.php";
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

    public function editFilms($id) {

        $pdo = Connect::seConnecter();

        if(!Service::exist("film", $id)) {
                header("Location:index.php?action=listFilm");
                exit;
        } else {

        $requete = $pdo->prepare("
        SELECT
                film.id_film,
                film.titre AS titre,
                film.dateDeSortie,
                film.duree,
                film.synopsis AS synopsis,
                film.note,
                film.affiche,
                film.id_realisateur AS idReal,
                categoriser.id_categorie AS idCateg,
                CONCAT(personne.prenom, ' ',personne.nom) AS lesRealisateur
        FROM film
        INNER JOIN realisateur ON film.id_realisateur = realisateur.id_realisateur
        INNER JOIN personne ON realisateur.id_personne = personne.id_personne
        INNER JOIN categoriser ON film.id_film = categoriser.id_film
        WHERE film.id_film = :id
        ");

        $requete->execute(["id" => $id]);

        $requeteReal = $pdo->query("
        SELECT
                CONCAT(personne.prenom, ' ',personne.nom) AS lesRealisateur, 
                realisateur.id_realisateur AS idRealisateur
        FROM realisateur
        INNER JOIN personne ON realisateur.id_personne = personne.id_personne
        ");

        $requeteCategorie = $pdo->query("
        SELECT 
                categorie.id_categorie AS idCategorie,
                categorie.`type` AS typeCategorie
        FROM categorie
        ");

        require "view/formulaire/editFilm.php";
        }
    }
}

<?php 


namespace Controller;
use Model\Connect;
use Model\Service;

class FilmController { 

    public function listFilm() { // Requete pour l'affichage de la liste des films

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


    public function detFilm($id) { // Requète pour afficher le detail d'un film
        
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
                        CONCAT(personne.prenom, ' ',personne.nom) AS nomActeurs,
                        acteur.id_acteur AS idActeur
                FROM joue
                INNER JOIN film ON joue.id_film = film.id_film
                INNER JOIN acteur ON joue.id_acteur = acteur.id_acteur
                INNER JOIN personne ON acteur.id_personne = personne.id_personne
                INNER JOIN role ON joue.id_role = role.id_role
                WHERE film.id_film = :id
                ");
                $requeteActeurRole->execute(["id" => $id]);
        
                // Requete pour afficher mes catégories
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


    public function gestionFilm() { // Récupère les éléments pour la gestion du formulaire d'ajout d'un film (le choix du réalisateur et des catégories)

        $pdo = Connect::seConnecter();

        // Requete pour les réalisateurs
        $requeteReal = $pdo->query("
        SELECT
                CONCAT(personne.prenom, ' ',personne.nom) AS lesRealisateur, 
                realisateur.id_realisateur AS idRealisateur
        FROM realisateur
        INNER JOIN personne ON realisateur.id_personne = personne.id_personne
        ");

        // Requete pour les catégories
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


            // Je teste mes conditions et j'affiche un message personnalisé en fonction de l'erreur
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

            } else if ($size >= $maxSize) { // Si la taille de l'image n'est pas correct
                                
                        $_SESSION['message'] .= "<p> Problème avec la taille de votre image. Merci de changer votre image. (taille max : 1mo)</p>";
                        header("Location:index.php?action=gestionFilm");

            } elseif ($erreur > 0) { // Si l'image à une erreur

                        $_SESSION['message'] .= "<p> Erreur upload file. </p>";
                        header("Location:index.php?action=gestionFilm");

            }  elseif ($condition1 && $condition2 && $size <= $maxSize && $erreur == 0) { // Si tout vas bien, on ajoute le film
                    
                    $uniqueName = uniqid('', true);
                    //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
                    $file = $uniqueName.".".$extension;
                    move_uploaded_file($tmpName, './public/img/afficheFilm/'.$file);

                        // Je récupère mon image dans le dossier
                        $image = imagecreatefromstring(file_get_contents('./public/img/afficheFilm/' . $file));
                        // Je prépare ma nouvelle image
                        $webpPath = "./public/img/afficheFilm/" . $uniqueName . ".webp";
                        // Je convertie en webP
                        imagewebp($image, $webpPath);
                        // Et je supprime mon ancienne image
                        unlink('./public/img/afficheFilm/'.$file);

                        // Je définie le chemin pour le BDD
                        $cheminfile = $webpPath;

                    // Je prépare et j'ajoute le film
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

                            // Je récupère l'id du dernier élément inséré (ici l'id du film)
                    $lastFilm = $pdo->lastInsertId();

                    // Et je crée une boucle pour lui ajouter les catégories sélectionner.
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

    public function editerFilm($id) { // Je récupère les éléments pour mon formulaire d'édit, et pouvoir les afficher directement dans le formulaire

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

        $requeteCategoriesFilm = $pdo->prepare("
        SELECT id_categorie
        FROM categoriser
        WHERE id_film = :id
        ");
        $requeteCategoriesFilm->execute(["id" => $id]);

        require "view/film/editFilm.php";
        }
    }

    public function editFilm($id) { // Function pour editer un film (traitement)

        session_start();
        $pdo = Connect::seConnecter();

        // Je vérifie que j'ai bien recu via le formulaire l'action :

        if(isset($_GET['action'])) {

            if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {

                // Je récupère le lien de l'affiche du film
                $requeteAffiche = $pdo->prepare("
                SELECT film.affiche
                FROM film
                WHERE film.id_film = :id ");

                $requeteAffiche->execute(['id' => $id]);

                $afficheFilm = $requeteAffiche->fetch();
                $lienAffiche = $afficheFilm['affiche'];

                // Si elle existe, je la supprime pour ajouter la nouvelle
                if(isset($lienAffiche)) {

                        unlink($lienAffiche);

                }

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

                $uniqueName = uniqid('', true);
                //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086

                if ($size >= $maxSize) { // Si la taille de l'image n'est pas correct
                                
                        $_SESSION['message'] .= "<p> Problème avec la taille de votre image. Merci de changer votre image. (taille max : 1mo)</p>";
                        header("Location:index.php?action=editerFilm&id=$id");

                } elseif ($erreur > 0) { // Si l'image à une erreur

                        $_SESSION['message'] .= "<p> Erreur upload file. </p>";
                        header("Location:index.php?action=editerFilm&id=$id");

                } else {

                $file = $uniqueName.".".$extension;
                move_uploaded_file($tmpName, './public/img/afficheFilm/'.$file);

                // Je crée le chemin de l'image pour la BDD
                $cheminfile = "public/img/afficheFilm/" . $file;

                $requeteModifierAffiche = $pdo->prepare("
                UPDATE film
                SET 
                        affiche = :affiche
                WHERE id_film = :id
                ");

                $requeteModifierAffiche->execute([
                        'affiche' => $cheminfile,
                        'id' => $id]);

            }}
            // Filtrage des données
            $titre = ucfirst(filter_input(INPUT_POST, "titre", FILTER_SANITIZE_SPECIAL_CHARS));
            $dateDeSortie = filter_input(INPUT_POST, "dateSortie", FILTER_SANITIZE_SPECIAL_CHARS);
            $duree = filter_input(INPUT_POST, "duree", FILTER_VALIDATE_INT);
            $synopsys = filter_input(INPUT_POST, "synopsys", FILTER_SANITIZE_SPECIAL_CHARS);
            $note = filter_input(INPUT_POST, "note", FILTER_VALIDATE_INT);
            $realisateur = filter_input(INPUT_POST, "realisateur", FILTER_VALIDATE_INT);

             // Je crée deux condition pour ne pas faire un IF à rallonge
            $condition1 = !empty($titre) && strlen($titre) <= 20 && preg_match("/^[A-Za-z0-9 '-]+$/", $titre);
            $condition2 = is_numeric($duree) && is_numeric($note) && is_numeric($realisateur);

            // Je teste chaque conditions pour afficher un message personnalisé en fonction de l'erreur
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

            }  elseif ($condition1 && $condition2) { // Et si tout vas bien, j'edit mon film

                // J'edit ici le film
                $requete = $pdo->prepare("
                UPDATE film
                SET 
                        titre = :titre,
                        dateDeSortie = :dateDeSortie,
                        duree = :duree,
                        synopsis = :synopsis,
                        note = :note,
                        id_realisateur = :id_realisateur
                WHERE film.id_film = :id
                ");

                $requete->execute([
                        'titre' => $titre,
                        'dateDeSortie' => $dateDeSortie, 
                        'duree' => $duree, 
                        'synopsis' => $synopsys, 
                        'note' => $note,
                        'id_realisateur' => $realisateur,
                        'id' => $id]);

                // Je delete les catégories que le film posseder, pour pouvoir lui ajouter les nouvelles valeur
                $requeteDeleteCategories = $pdo->prepare("
                        DELETE FROM categoriser
                        WHERE id_film = :id_film 
                        ");

                $requeteDeleteCategories->execute(['id_film' => $id]);

                // J'ajoute ici les nouvelles valeur de catégories au film
                foreach ($_POST['categorie'] as $categorieFilm) {
                        $categorieFilm = filter_var($categorieFilm, FILTER_VALIDATE_INT);
                        
                        if ($categorieFilm) {


                $requeteCategorie = $pdo->prepare("
                        INSERT INTO categoriser (id_film, id_categorie)
                        VALUES (:id_film, :id_categorie)
                        ON DUPLICATE KEY UPDATE id_categorie = :id_categorie
                        ");

                $requeteCategorie->execute([
                        'id_film' => $id,
                        'id_categorie' => $categorieFilm
                        ]);
                }
                }
                        
                }
        
        $_SESSION['message'] .= "<p> Modification reussi !</p>
                                <a href='index.php?action=detFilm&id=". $id . "'> Accès au film </a>";
        header("Location:index.php?action=editerFilm&id=$id");

        }
     }

    
     public function delFilm($id) { // Function pour delete un film (traitement)
        $pdo = Connect::seConnecter();

        if(!Service::exist("film", $id)) {
                header("Location:index.php?action=listFilm");
                exit;
        } else {

        // Je récupère l'url de l'affiche du film
        $requeteAffiche = $pdo->prepare("
        SELECT film.affiche
        FROM film
        WHERE film.id_film = :id ");
        
        $requeteAffiche->execute(['id' => $id]);
        
        $afficheFilm = $requeteAffiche->fetch();
        $lienAffiche = $afficheFilm['affiche'];
        
        // Si elle existe, je la supprime pour ajouter la nouvelle
        if(isset($lienAffiche)) {
        
                unlink($lienAffiche);
        
        }

        // Je supprime les roles du film
        $requeteRole = $pdo->prepare("
        DELETE FROM joue
        WHERE id_film = :id
        ");

        $requeteRole->execute(["id" => $id]);

        // Je supprime aussi les liens avec les catégories
        $requeteCategoriser = $pdo->prepare("
        DELETE FROM categoriser
        WHERE id_film = :id
        ");

        $requeteCategoriser->execute(["id" => $id]);

        // Puis je supprime le film.
        $requete = $pdo->prepare("
        DELETE FROM film
        WHERE id_film = :id
        ");

        $requete->execute(["id" => $id]);
        header("Location:index.php");
        }

     }

     public function delRoleFilm($id) { // Fonction pour supprimer un casting 

        $pdo = Connect::seConnecter();

        // Je récupère l'idActeur et Role depuis l'url
        $idActeur = (isset($_GET["idActeur"])) ? $_GET["idActeur"] : null;
        $idRole = (isset($_GET["idRole"])) ? $_GET["idRole"] : null;

        if(!Service::exist("film", $id) && !Service::exist("acteur", $idActeur) && !Service::exist("role", $idRole)) {
                header("Location:index.php?action=detFilm&id=$id");
                exit;
        } else {

                // Puis je delete dans la table joue ce que je désire
                $requeteRole = $pdo->prepare("
                DELETE FROM joue
                WHERE id_film = :id
                AND id_acteur = :idActeur
                AND id_role = :idRole
                ");
        
                $requeteRole->execute([
                        "id" => $id,
                        "idActeur" => $idActeur,
                        "idRole" => $idRole
                ]);
                header("location:" . $_SERVER['HTTP_REFERER']);
     }

}

}
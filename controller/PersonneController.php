<?php 


namespace Controller;
use Model\Connect;
use Model\Service;

class PersonneController { 

    public function listActeur() { // Requete pour afficher la liste de tout les acteurs

        $pdo = Connect::seConnecter();

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

    public function listRealisateur() { // Requete pour afficher la liste de tout les réalisateurs

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

    public function detPersonne($id) { // Requete pour afficher le detail d'une personne

        $pdo = Connect::seConnecter();

        if(!Service::exist("personne", $id)) {
                header("Location:index.php");
                exit;
        } else {

        // Requete pour afficher les infos d'une seul personne
        $requete = $pdo->prepare("
        SELECT
                personne.id_personne,
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
                DATE_FORMAT(film.dateDeSortie, '%Y') AS dateSortie,
                acteur.id_acteur AS idActeur
        FROM joue
        INNER JOIN acteur ON joue.id_acteur = acteur.id_acteur
        INNER JOIN personne ON acteur.id_personne = personne.id_personne
        INNER JOIN film ON joue.id_film = film.id_film
        INNER JOIN role ON joue.id_role = role.id_role
        WHERE personne.id_personne = :id
        ORDER BY dateSortie
        ");
        $requeteFilmoActeur->execute(["id" => $id]);

        // affiche la filmographie d'un réalisateur (film/titre/année)
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
    }

    public function gestionPersonne() { // Affiche la vue gestion Personne
        require "view/formulaire/gestionPersonne.php";
    }

    public function addPersonne() { // Permet l'ajout d'une personne

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
                $extensions = ['jpg', 'png', 'jpeg', 'webp'];
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

                        $uniqueName = uniqid('', true);
                        //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
                        $file = $uniqueName.".".$extension;
                        move_uploaded_file($tmpName, './public/img/personne/'.$file);
    
                            // Je récupère mon image dans le dossier
                            $image = imagecreatefromstring(file_get_contents('./public/img/personne/' . $file));
                            // Je prépare ma nouvelle image
                            $webpPath = "./public/img/personne/" . $uniqueName . ".webp";
                            // Je convertie en webP
                            imagewebp($image, $webpPath);
                            // Et je supprime mon ancienne image
                            unlink('./public/img/personne/'.$file);
    
                            // Je définie le chemin pour le BDD
                            $cheminfile = $webpPath;

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

    public function editerPersonne($id) { // Affiche le formulaire pour l'édition d'une personne

        $pdo = Connect::seConnecter();

        if(!Service::exist("personne", $id)) {
                header("Location:index.php");
                exit;
        } else {

        $requete = $pdo->prepare("
        SELECT 
                personne.id_personne,
                personne.nom,
                personne.prenom,
                personne.sexe,
                personne.dateDeNaissance,
                personne.photo,
                acteur.id_acteur AS idActeur,
                realisateur.id_realisateur AS idRealisateur
        FROM personne
        LEFT JOIN acteur ON personne.id_personne = acteur.id_personne
        LEFT JOIN realisateur ON personne.id_personne = realisateur.id_personne
        WHERE personne.id_personne = :id
        ");

        $requete->execute(["id" => $id]);


        require "view/personne/editPersonne.php";
    }
    }

    public function editPersonne($id) { // Traitement de l'édition d'une personne

                session_start();
                $pdo = Connect::seConnecter();

                // Je vérifie que j'ai bien recu via le formulaire l'action :
        
                if(isset($_GET['action']) && isset($_GET['type'])) {
        
                    if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                        
                        // Si il y a une nouvelle image dans la modification :
                        
                        // Je récupère le lien de la photo actuel
                        $requetePhoto = $pdo->prepare("
                        SELECT personne.photo
                        FROM personne
                        WHERE personne.id_personne = :id ");
        
                        $requetePhoto->execute(['id' => $id]);
        
                        $photoPersonne = $requetePhoto->fetch();
                        
                        // Et j'enrengistre le lien dans une variable
                        $lienPhotoPersonne = $photoPersonne['photo'];

                        // Si la variable à reçu un élément, on supprime l'image
                        if(isset($lienPhotoPersonne)) {

                                unlink($lienPhotoPersonne);

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
                        $extensions = ['jpg', 'png', 'jpeg', 'webp'];
                        // Et je crée une condition pour la taille MAX d'une image (1 MO ici)
                        $maxSize = 1000000;
        
                        $uniqueName = uniqid('', true);

                        if ($size >= $maxSize) { // Si la taille de l'image n'est pas correct
                                
                                $_SESSION['message'] .= "<p> Problème avec la taille de votre image. Merci de changer votre image. (taille max : 1mo)</p>";
                                header("Location:index.php?action=editerPersonne&id=$id");

                        } elseif ($erreur > 0) { // Si l'image à une erreur

                                $_SESSION['message'] .= "<p> Erreur upload file. </p>";
                                header("Location:index.php?action=editerPersonne&id=$id");

                        } else {
                                
                        //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
                        $file = $uniqueName.".".$extension;
                        move_uploaded_file($tmpName, './public/img/personne/'.$file);

                        // Je récupère mon image dans le dossier
                        $image = imagecreatefromstring(file_get_contents('./public/img/personne/' . $file));
                        // Je prépare ma nouvelle image
                        $webpPath = "./public/img/personne/" . $uniqueName . ".webp";
                        // Je convertie en webP
                        imagewebp($image, $webpPath);
                        // Et je supprime mon ancienne image
                        unlink('./public/img/personne/'.$file);

                        // Je définie le chemin pour le BDD
                        $cheminfile = $webpPath;
                        
                        // Puis j'update la nouvelle photo dans le champ photo de ma personne
                        $requeteModifierPhoto = $pdo->prepare("
                        UPDATE personne
                        SET 
                                photo = :photo
                        WHERE id_personne = :id
                        ");
        
                        $requeteModifierPhoto->execute([
                                'photo' => $cheminfile,
                                'id' => $id]);
        
                    }}
                

                // Après la gestion des photos, on gère le formulaire. On commence par un tri des valeurs reçu.
                $lastname = filter_input(INPUT_POST, "last", FILTER_SANITIZE_SPECIAL_CHARS);
                $lastname = ucfirst($lastname);
                $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
                $name = ucfirst($name);
                $genre = filter_input(INPUT_POST, "genre", FILTER_SANITIZE_SPECIAL_CHARS);
                $dateNaissance = filter_input(INPUT_POST, "dateNaissance", FILTER_SANITIZE_SPECIAL_CHARS);
                $metier = filter_input(INPUT_POST, "metier", FILTER_SANITIZE_SPECIAL_CHARS);

                $condition1 = !empty($lastname) && strlen($lastname) <= 20 && preg_match("/^[A-Za-z '-]+$/", $lastname);
                $condition2 = !empty($name) && strlen($name) <= 20 && preg_match("/^[A-Za-z '-]+$/", $name);

                if(!$condition1) { // Si le nom à un problème
                        $_SESSION['message'] .= "<p> Problème avec le nom. Merci de changer le nom </p>";
                        header("Location:index.php?action=editerPersonne&id=$id");

                } elseif (!$condition2) { // si le prénom à un problème
                        $_SESSION['message'] .= "<p> Problème avec le prénom. Merci de changer le prénom </p>";
                        header("Location:index.php?action=editerPersonne&id=$id");

                } elseif ($genre !== "M" && $genre !== "F") { // si le genre n'est pas bon
                        $_SESSION['message'] .= "<p> Problème avec le genre. Merci de changer le genre. </p>";
                        header("Location:index.php?action=editerPersonne&id=$id");
                }
                   // Si tout vas bien, je crée une condition total puis j'edit la personne dans la BDD
                elseif ($condition1 && $condition2 && ($genre == "M" || $genre == "F") && $dateNaissance && $metier) {
                
                        $requete = $pdo->prepare("                
                        UPDATE personne
                        SET 
                                nom = :nom,
                                prenom = :prenom,
                                sexe = :sexe,
                                dateDeNaissance = :dateDeNaissance
                        WHERE id_personne = :id
                        ");

                        $requete->execute([
                                'nom' => $lastname,
                                'prenom' => $name, 
                                'sexe' => $genre, 
                                'dateDeNaissance' => $dateNaissance,
                                'id' => $id]);
                }

                // Je m'occupe ici de la gestion de la modification d'une personne en Acteur/Réalisateur/Les deux
                if(isset($_GET['type'])) {

                        // Je récupère le type de ma personne (acteur/réalisateur/les deux)
                        $requeteType = $pdo->prepare("
                        SELECT
                                personne.id_personne,
                                acteur.id_personne AS idActor,
                                realisateur.id_personne AS idReal
                        FROM personne
                        LEFT JOIN acteur ON personne.id_personne = acteur.id_personne
                        LEFT JOIN realisateur ON personne.id_personne = realisateur.id_personne
                        WHERE personne.id_personne = :id
                        ");

                        // Je vérifie si il y a des films en tant que réalisateur avec l'id de la personne
                        $requeteTypeCheckFilm = $pdo->prepare("
                        SELECT
                                film.id_realisateur AS idFilmReal
                        FROM film
                        LEFT JOIN realisateur ON film.id_realisateur = realisateur.id_realisateur
                        LEFT JOIN personne ON realisateur.id_personne = personne.id_personne
                        WHERE personne.id_personne = :id
                        ");

                        // Je vérifie si il y a des roles en tant qu'acteur avec l'id de la personne
                        $requeteTypeCheckRole = $pdo->prepare("
                        SELECT
                                joue.id_acteur AS idActeurRole
                        FROM joue
                        LEFT JOIN acteur ON joue.id_acteur = acteur.id_acteur
                        LEFT JOIN personne ON acteur.id_personne = personne.id_personne
                        WHERE personne.id_personne = :id
                        ");

                        // J'execute les requetes avec l'id
                        $requeteType->execute(["id" => $id]);
                        $requeteTypeCheckFilm->execute(["id" => $id]);
                        $requeteTypeCheckRole->execute(["id" => $id]);

                        $typeActeurRealisateur = $requeteType->fetch();
                        $typeFilmRealisateur = $requeteTypeCheckFilm->fetch();
                        $typeRoleActeur = $requeteTypeCheckRole->fetch();

                        // J'insère les infos dans des variables pour pouvoir les tester
                        $idActeur = $typeActeurRealisateur['idActor'];
                        $idRealisateur = $typeActeurRealisateur['idReal'];
                        $idFilmRealisateur = $typeFilmRealisateur['idFilmReal'];
                        $idRoleActeur = $typeRoleActeur['idActeurRole'];

                        // SI ON MODIFIE EN TANT QU'ACTEUR :
                        if($_GET['type'] == "acteur") {
                                // Si c'était un réalisateur et qu'il à des films en tant que réalisateur, on empêche la suppression.
                                if($idRealisateur !== null && $idFilmRealisateur !== null) {

                                        $_SESSION['message'] .= "<p> Vous ne pouvez pas modifier un réalisateur en acteur alors qu'il à un film dans sa filmographie. Modifier d'abord le film.</p>";
                                        header("Location:index.php?action=editerPersonne&id=$id");
                                        exit;

                                        // Sinon, si c'etait un réalisateur sans film, je le supprime de la table realisateur.
                                } elseif ($idRealisateur !== null) {
                                        $requeteRealisateur = $pdo->prepare("
                                        DELETE FROM realisateur 
                                        WHERE id_personne = :id
                                        ");
                                        $requeteRealisateur->execute(["id" => $id]);

                                        // Puis je le rajoute en acteur (Je vérifie la condition dans le cas ou on passerai de "Les deux" à "Acteur" uniquement, dans quel cas il existerai déjà dans la table acteur)
                                        if($idActeur === null) {
        
                                                $requeteActeur = $pdo->prepare("
                                                INSERT INTO acteur
                                                        (id_personne)
                                                VALUES
                                                        (:id_personne)
                                                ");
                                                $requeteActeur->execute(['id_personne' => $id]);
                                        }
                                }
                                
                                
                        }

                        // SI ON MODIFIE EN TANT QUE REALISATEUR :
                        if($_GET['type'] == "realisateur") {

                                // Si c'était un acteur et qu'il avais des rôles en cours dans des films, on empêche la suppression
                                if($idRoleActeur !== null && $idActeur !== null) {

                                        $_SESSION['message'] .= "<p> Vous ne pouvez pas modifier un acteur qui à des rôles dans des films. Supprimer d'abord les roles.</p>
                                        <a href='index.php?action=detPersonne&id=$id'>Role</a>";
                                        header("Location:index.php?action=editerPersonne&id=$id");
                                        exit;

                                // Sinon, si c'était un acteur sans rôle, je le supprime de la table acteur.
                                } elseif ($idActeur !== null) {
                                        $requeteActeur = $pdo->prepare("
                                        DELETE FROM acteur 
                                        WHERE id_personne = :id
                                        ");
                                        $requeteActeur->execute(["id" => $id]);

                                        // Puis je le rajoute en réalisateur (Je vérifie la condition dans le cas ou on passerai de "Les deux" à "réalisateur" uniquement, dans quel cas il existerai déjà dans la table réalisateur)
                                        if($idRealisateur === null) {
        
                                                $requeteRealisateur = $pdo->prepare("
                                                INSERT INTO realisateur
                                                        (id_personne)
                                                VALUES
                                                        (:id_personne)
                                                ");
                                                $requeteRealisateur->execute(['id_personne' => $id]);
                                        }
                                }
                                
                                
                        }

                        // SI ON MODIFIE POUR "LES DEUX"
                        if($_GET['type'] == "lesdeux") {     
                                
                                // Si il n'existe pas en tant que réalisateur, je l'ajoute.
                                if($idRealisateur === null) {
        
                                        $requeteRealisateur = $pdo->prepare("
                                        INSERT INTO realisateur
                                                (id_personne)
                                        VALUES
                                                (:id_personne)
                                        ");
                                        $requeteRealisateur->execute(['id_personne' => $id]);
                                }

                                // Si il n'existe pas en tant qu'acteur, je l'ajoute.
                                if($idActeur === null) {
        
                                        $requeteActeur = $pdo->prepare("
                                        INSERT INTO acteur
                                                (id_personne)
                                        VALUES
                                                (:id_personne)
                                        ");
                                        $requeteActeur->execute(['id_personne' => $id]);
                                }
                        }
                }


        $_SESSION['message'] .= "<p> Modification reussi !</p>
        <a href='index.php?action=detPersonne&id=". $id . "'> Accès à la personne </a>";
        header("Location:index.php?action=editerPersonne&id=$id");
}
    }

    public function delPersonne($id) { // Traitement pour la suppression d'une personne
        session_start();
        $pdo = Connect::seConnecter();

        // Si la personne est un acteur, je récupère son id via l'url
        $idActeur = (isset($_GET["idActeur"])) ? $_GET["idActeur"] : null;

        if(!Service::exist("personne", $id)) {

                if(!Service::exist("acteur", $idActeur)) {
                        header("Location:index.php");
                        exit; 
                }

                header("Location:index.php");
                exit;
        } else {

                // Si l'id de l'acteur n'est pas null je supprime ses roles :
                if($idActeur !== null) {
                $requeteJoue = $pdo->prepare("
                DELETE FROM joue
                WHERE id_acteur = :idActeur
                ");
                
                $requeteJoue->execute(["idActeur" => $idActeur]);
            }
                
                // Puis je supprime la personne de la table acteur
                $requeteActeur = $pdo->prepare("
                DELETE FROM acteur
                WHERE id_personne = :id
                ");
        
                $requeteActeur->execute(["id" => $id]);


                // Je récupère les informations de ma personne (si il est acteur ou réalisateur)
                $requeteType = $pdo->prepare("
                SELECT
                        personne.id_personne,
                        acteur.id_personne AS idActor,
                        realisateur.id_personne AS idReal
                FROM personne
                LEFT JOIN acteur ON personne.id_personne = acteur.id_personne
                LEFT JOIN realisateur ON personne.id_personne = realisateur.id_personne
                WHERE personne.id_personne = :id
                ");

                // Je vérifie si il y a un film associés à mon réalisateur
                $requeteTypeCheckFilm = $pdo->prepare("
                SELECT
                        film.id_realisateur AS idFilmReal
                FROM film
                LEFT JOIN realisateur ON film.id_realisateur = realisateur.id_realisateur
                LEFT JOIN personne ON realisateur.id_personne = personne.id_personne
                WHERE personne.id_personne = :id
                ");

                $requeteType->execute(["id" => $id]);
                $requeteTypeCheckFilm->execute(["id" => $id]);

                $typeActeurRealisateur = $requeteType->fetch();
                $typeFilmRealisateur = $requeteTypeCheckFilm->fetch();

                $idRealisateur = $typeActeurRealisateur['idReal'];
                $idFilmRealisateur = $typeFilmRealisateur['idFilmReal'];

                // Si c'est un réalisateur et qu'il à des films associés, j'empêche la suppression
                if($idRealisateur !== null && $idFilmRealisateur !== null) {

                $_SESSION['message'] .= "<p> Vous ne pouvez pas supprimer une personne qui à réaliser un film. Modifier ou supprimer d'abord le film.</p>";
                header("location:" . $_SERVER['HTTP_REFERER']);
                exit;

                } else {
        
                        // sinon je peux supprimer la personne dans la table réalisateur
                $requeteRealisateur = $pdo->prepare("
                DELETE FROM realisateur
                WHERE id_personne = :id
                ");
                
                $requeteRealisateur->execute(["id" => $id]);

                }

                // Avant de supprimer la personne, je récupère le lien de sa photo pour la supprimer du dossier
                $requetePhoto = $pdo->prepare("
                SELECT personne.photo
                FROM personne
                WHERE personne.id_personne = :id ");
                
                $requetePhoto->execute(['id' => $id]);
                
                $photoPersonne = $requetePhoto->fetch();
                $lienPhoto = $photoPersonne['photo'];
                
                // Si elle existe, je la supprime pour ajouter la nouvelle
                if(isset($lienPhoto)) {
                
                        unlink($lienPhoto);
                
                }

                // Et je supprime finalement ma personne de la table personne
                $requete = $pdo->prepare("
                DELETE FROM personne
                WHERE id_personne = :id
                ");

                $requete->execute(["id" => $id]);

                header("Location:index.php");
                }

    }


}



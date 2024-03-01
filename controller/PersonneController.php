<?php 


namespace Controller;
use Model\Connect;
use Model\Service;

class PersonneController { 

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

        if(!Service::exist("personne", $id)) {
                header("Location:index.php");
                exit;
        } else {

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
    }

    public function gestionPersonne() {
        require "view/formulaire/gestionPersonne.php";
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
<?php 


namespace Controller;
use Model\Connect;
use Model\Service;

class CinemaController {


     public function afficheTitrefilm() {

        $pdo = Connect::seConnecter();

        // Requete pour l'affichage des films sur l'acceuil
        $requete = $pdo->query("
                SELECT 
                    film.affiche, 
                    film.titre, 
                    film.id_film,
                    film.note,
                    film.synopsis
                FROM film
                ORDER BY film.dateDeSortie DESC
                LIMIT 2
                ");

        // Requete pour l'affichage des acteurs sur l'acceuil
        $requeteA = $pdo->query("
        SELECT  personne.photo,
                CONCAT(personne.prenom, ' ',personne.nom) AS acteur,
                personne.id_personne,
                COUNT(joue.id_role) AS nbRole
        FROM acteur
        INNER JOIN personne ON acteur.id_personne = personne.id_personne
        INNER JOIN joue ON acteur.id_acteur = joue.id_acteur
        GROUP BY acteur.id_acteur
        ORDER BY nbRole DESC
        LIMIT 3
                ");

        // Requete pour l'affichage des réalisateurs sur l'acceuil
        $requeteR = $pdo->query("
        SELECT 
                personne.photo, 
                CONCAT(personne.prenom, ' ',personne.nom) AS realisateur,
                personne.id_personne,
                COUNT(film.id_realisateur) AS nbFilm
        FROM realisateur
        INNER JOIN personne ON realisateur.id_personne = personne.id_personne
        INNER JOIN film ON realisateur.id_realisateur = film.id_realisateur
	GROUP BY realisateur.id_realisateur
	ORDER BY nbFilm DESC
	LIMIT 3
                ");
        require "view/acceuil.php";
     }

     public function gestion() { // Affiche la vue Gestion.php pour l'affichage de la page
        require "view/formulaire/gestion.php";
     }

     public function research() { // Function permettant d'utiliser la barre de recherche
        $pdo = Connect::seConnecter();

        if (isset($_GET['action'])) { // Si j'ai une action dans l'url

                // Je filtre la recherche
                $research = filter_input(INPUT_POST, "research", FILTER_SANITIZE_SPECIAL_CHARS);

                // Puis je recherche dans mes tables les éléments qui pourraient correspondre

                // Recherche dans les films
            $requeteFilm = $pdo->prepare("
            SELECT
                film.id_film,
                film.titre
            FROM film
            WHERE film.titre LIKE :research
            "); 
            
            $requeteFilm->execute(["research" => '%' . $research . '%']);

                // Recherche dans les personnes (acteurs/réalisateurs)
            $requetePersonne = $pdo->prepare("
            SELECT
                CONCAT(personne.prenom, ' ',personne.nom) AS nomPersonne,
                id_personne
            FROM personne
            WHERE personne.prenom LIKE :research
            OR personne.nom LIKE :research
            ");

            $requetePersonne->execute(["research" => '%' . $research . '%']);

                // Recherche dans les roles
            $requeteRole = $pdo->prepare("
            SELECT
                nom,
                id_role
            FROM role
            WHERE nom LIKE :research
            ");

            $requeteRole->execute(["research" => '%' . $research . '%']);

            // Recherche dans les catégories
            $requeteCategorie = $pdo->prepare("
            SELECT
                type,
                id_categorie
            FROM categorie
            WHERE type LIKE :research
            ");

            $requeteCategorie->execute(["research" => '%' . $research . '%']);

            require "view/research.php";
        }
     }

     public function register() {

        require "view/formulaire/register.php";

     }

     public function addUser() {
         
         if($_POST["submit"]) { 

            $pdo = Connect::seConnecter();

            $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_VALIDATE_EMAIL);
            $pass1 = filter_input(INPUT_POST, "pass1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pass2 = filter_input(INPUT_POST, "pass2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($pseudo && $email && $pass1 && $pass2) { 

                // Je teste via une requête si le MAIL est déjà existant dans ma BDD
                $requeteMail = $pdo->prepare("SELECT * FROM user WHERE email = :email");
                $requeteMail->execute(["email" => $email]);

                $userEmail = $requeteMail->fetch();

                // Je teste via une requête si mon PSEUDO est déjà existant dans ma BDD
                $requetePseudo = $pdo->prepare("SELECT * FROM user WHERE pseudo = :pseudo");
                $requetePseudo->execute(["pseudo" => $pseudo]);

                $userPseudo = $requetePseudo->fetch();

                // Si le mail existe déjà, j'envoi un message d'erreur
                if($userEmail) {
                    $_SESSION['message'] = "Email déjà existante";
                    header("Location: index.php?action=register"); exit;

                // Si le pseudo existe déjà, j'envoi un message d'erreur
                } elseif ($userPseudo) {
                    $_SESSION['message'] = "Pseudo déjà utilisé";
                    header("Location: index.php?action=register"); exit;

                // Si c'est ok, je passe à la suite
                } else {

                    // Je vérifie que mon MDP soit identique et qu'il est une taille adapté (12 caractères CNIL) (4 ici pour les testes)
                    if($pass1 == $pass2 && strlen($pass1) > 4) { 

                        // Si c'est ok, j'ajoute en BDD
                        $insertUser = $pdo->prepare("INSERT INTO user (pseudo, email, password) VALUES (:pseudo, :email, :password)");

                        $insertUser->execute([
                            "pseudo" => $pseudo,
                            "email" => $email,
                            "password" => password_hash($pass1, PASSWORD_DEFAULT)
                        ]);

                        $_SESSION['message'] = "Inscription reussi ! Vous pouvez vous connectez.";
                        header("Location: index.php?action=login"); exit;

                    } else { // Sinon, message d'erreur

                        $_SESSION['message'] = "Mots de passe différent";
                        header("Location: index.php?action=register"); exit;
                    }
                }

            } else { // Si la vérification des données est fausse
                header("Location: index.php?action=register"); exit;
            }
        } else { // Si on est sur la page mais pas depuis le formulaire.
            header("Location: index.php?action=register"); exit;
        }
    }

    public function connexion() {
        require "view/formulaire/login.php";
    }

    public function login() {
        if($_POST["submit"]) { 

            $pdo = Connect::seConnecter();

            // Filtrage de la saisie du formulaire (faille XSS)
            $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($pseudo && $password) { 

                // Je vérifie que le pseudo existe (prepare -> injection SQL)
                $requete = $pdo->prepare("SELECT * FROM user WHERE pseudo = :pseudo");
                $requete->execute(["pseudo" => $pseudo]);

                $user = $requete->fetch();

                if ($user) { 

                    // Je récupère le hash du mot de passe
                    $hash = $user['password'];

                    // Et je vérifie qu'il correspond au mots de passe taper
                    if(password_verify($password, $hash)) { 

                        // Si c'est le cas, j'enrengistre l'user en session. IL EST DESORMAIS CONNECTER !
                        $_SESSION['user'] = $user;
                        header("Location: index.php"); exit;                        

                    } else {
                        $_SESSION['message'] = "mot de passe incorrect.";
                        header("Location: index.php?action=connexion"); exit;
                    }


                } else {
                    $_SESSION['message'] = "Le pseudo n'existe pas.";
                    header("Location: index.php?action=connexion"); exit;
                }

            } else {
                header("Location: index.php?action=connexion"); exit;
            }
        }

        header("Location: index.php?action=connexion"); exit;
        
    }

    public function logout() {

        unset($_SESSION['user']);
        header("Location: index.php"); exit;
    }
   
}
?>
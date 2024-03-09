<?php 


namespace Controller;
use Model\Connect;
use Model\Service;

class RoleController { 


    public function listRole() { // Requête pour l'affichage de ma liste de role

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

    public function detRole($id) { // Requête pour l'affichae des details d'un role

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

    public function gestionRole() { // Affiche la vue du formulaire pour l'ajout de rôle
        require "view/formulaire/gestionRole.php";
    }

    public function addRole() { // Traitement de l'ajout d'un role

        

        $pdo = Connect::seConnecter();

        // Si j'ai bien une action :
        if(isset($_GET['action'])) {

                // Je filtre la valeur reçu
                $role = filter_input(INPUT_POST, "role", FILTER_SANITIZE_SPECIAL_CHARS);

                // Je vérifie qu'elle à certaines conditions
                if(!empty($role) && strlen($role) <= 20 && preg_match("/^[A-Za-z '-]+$/", $role)) {

                        // Si oui, je l'ajoute
                        $requete = $pdo->prepare("
                        INSERT INTO role
                           (nom)
                        VALUES
                           (:nom)
                        ");

                        $requete->execute(['nom' => $role]);

                        $_SESSION['message'] = "<p> Votre role à bien été enrengistré ! </p>";
                        header("Location:index.php?action=gestionRole");
                        exit;
                }
                else { // Sinon, je préviens l'utilisateur
                        $_SESSION['message'] = "<p>Oups. Votre role n'as pas pu être enrengistré. Verifier vos informations.</p>";
                        header("Location:index.php?action=gestionRole");
                        exit;
                }
        }
    }

    public function editerRole($id) { // Requête pour l'affichage de la valeur d'un role dans le formulaire d'édition

        $pdo = Connect::seConnecter();

        if(!Service::exist("role", $id)) {
                header("Location:index.php?action=listRole");
                exit;
        } else {

                $requete = $pdo->prepare("
                SELECT 
                        role.id_role,
                        role.nom
                FROM role
                WHERE id_role = :id
                ");

                $requete->execute(["id" => $id]);
                require "view/role/editRole.php";
        }
}

    public function editRole($id) { // Traitement de l'édition d'un role

        

        $pdo = Connect::seConnecter();

        // Je vérifie que j'ai une action
        if(isset($_GET['action'])) {
                
                // Je filtre la nouvelle valeur reçu
                $role = filter_input(INPUT_POST, "role", FILTER_SANITIZE_SPECIAL_CHARS);

                // Je vérifie avec des conditions
                if(!empty($role) && strlen($role) <= 20 && preg_match("/^[A-Za-z '-]+$/", $role)) {

                        // Si tout va bien, je l'update
                        $requete = $pdo->prepare("
                        UPDATE role
                        SET 
                                nom = :role
                        WHERE id_role = :id
                        ");

                        $requete->execute([
                                'role' => $role,
                                'id' => $id]);

                        $_SESSION['message'] = "<p> Votre role à bien été enrengistré ! </p>
                                                <a href='index.php?action=detRole&id=". $id . "'> Accès au role </a>";
                        header("Location:index.php?action=editerRole&id=$id");
                        exit;
                }
                else { // Sinon, je préviens l'utilisateur d'un problème.
                        $_SESSION['message'] = "<p>Oups. Votre role n'as pas pu être enrengistré. Verifier vos informations.</p>";
                        header("Location:index.php?action=editerRole&id=$id");
                        exit;
                }
        }
    }

    public function delRole($id) {

        $pdo = Connect::seConnecter();

        if(!Service::exist("role", $id)) {
                header("Location:index.php?action=listRole");
                exit;
        } else {

                // Puis je delete dans la table joue ce que je désire
                $requeteJoue = $pdo->prepare("
                DELETE FROM joue
                WHERE id_role = :id
                ");

                $requeteRole = $pdo->prepare("
                DELETE FROM role
                WHERE id_role = :id
                ");
        
                $requeteJoue->execute([ "id" => $id]);
                $requeteRole->execute([ "id" => $id]);
                header("location:" . $_SERVER['HTTP_REFERER']);
                exit;
     }
    }
}
?>
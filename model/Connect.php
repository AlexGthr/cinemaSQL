<?php 

// Le namespace permet de définir un nom au class que l'on pourra ensuite charger de manière automatique grace à un autoloading.
namespace Model;


// Définition abstract en PDO : Une classe Abstraite est une classe qui ne va pas pouvoir être instanciée directement.
// C'est à dire qu'on ne va pas pouvoir manipuler directement la classe.
abstract class Connect {

    const HOST = "localhost";
    const DB = "cinema";
    const USER = "root";
    const PASS = "";

    // Static : Method déclarée statique est accessible sans création d'objet.
    // Les function static sont associées à la classe et non à une instance de la classe.
    public static function seConnecter() {
        try {
            return new \PDO("mysql:host=".self::HOST.";dbname=".self::DB.";charset=utf8", self::USER, self::PASS);
        } catch(\PDOException $ex) {
            return $ex->getMessage();
        }
    }
}

//Pourquoi PDO au lieu de MySQLi : l'extension MySQLi ne va fonctionner qu'avec les bases de données MySQL tandis que PDO va fonctionner avec 12 systèmes de bases de données différents

// Encapsulation class : Restrein les accès au données via la visibilité donnée par les mots cles donnée
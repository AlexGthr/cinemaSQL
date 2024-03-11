<?php

namespace Model;
use Model\Connect;

abstract class Service {

    public static function exist($table, $id) {

        $pdo = Connect::seConnecter();

        $exist = $pdo->prepare("SELECT * FROM $table WHERE id_$table = :id");
        $exist->execute(["id" => $id]);
        return $element = $exist->fetch();
    }
}



?>
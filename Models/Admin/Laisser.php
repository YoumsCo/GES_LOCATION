<?php

namespace Models\Admin;

use \Exception;
use Models\DBConnexion;

require_once '../../vendor/autoload.php';


class Laisser extends DBConnexion
{

    function read(): array {
        $sql = "select * from laisser order by date asc";

        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute();

            return $req->fetchAll();
        }
        catch(Exception $e) {
            die("Erreur : ". $e->getMessage() ."<br>A la ligne : ". $e->getLine());
        }
    }
    function procedure(): array {
        $sql = "CALL profilesComments()";
    
        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute();

            return $req->fetchAll();
        }
        catch(Exception $e) {
            die("Erreur : ". $e->getMessage() ."<br>A la ligne : ". $e->getLine() ."<br>Dans le fichier : ". $e->getFile());
        }
    }
}

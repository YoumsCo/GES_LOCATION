<?php

namespace Models\User;

use \Exception;
use Models\DBConnexion;

require_once '../../vendor/autoload.php';


class Laisser extends DBConnexion
{
    
    function create(int $iduser, int $id_avis) {
        $sql = "insert into laisser(iduser, id_avis) values(?, ?)";

        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$iduser, $id_avis]);
        }
        catch(Exception $e) {
            die("Erreur : ". $e->getMessage() ."<br>A la ligne : ". $e->getLine());
        }
    }
}

<?php

namespace Models\User;

use \Exception;
use Models\DBConnexion;
use Models\User\Laisser;

require_once '../../vendor/autoload.php';


class Avis extends DBConnexion
{

    private Laisser $laisser;

    function __construct()
    {
        $this->laisser = new Laisser();   
    }

    function get_reviews(): array
    {
        $sql = 'select * from avis order by id_avis desc';
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute();

        return $req->fetchAll();
    }

    function get_one(int $id): array
    {
        $sql = 'select * from avis where id_avis = ?'; 
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute([$id]);
        
        return $req->fetchAll();
    }
    
    function createReview(int $iduser, int $note, string $comment): void {
        $sql = "insert into avis(note, commentaire) values(?, ?)";

        try {
            DBConnexion::getConnexion()->beginTransaction();

            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$note, $comment]);
            $id_avis = DBConnexion::getConnexion()->lastInsertId();

            $this->laisser->create($iduser, $id_avis);
            
            DBConnexion::getConnexion()->commit();
        }
        catch(Exception $e) {
            DBConnexion::getConnexion()->rollBack();
            die("Erreur : ". $e->getMessage() ."<br>A la ligne : ". $e->getLine());
        }
    }
}

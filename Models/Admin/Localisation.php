<?php

namespace Models\Admin;

use Models\DBConnexion;

require_once '../../vendor/autoload.php';

use \Exception;
class Localisation extends DBConnexion
{
    function get_localisation(): array
    {
        $sql = 'select * from localisation';
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute();

        return $req->fetchAll();
    }

    function get_one(string $column, string $value): array
    {
        $sql = "select * from localisation where {$column} = ?";
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute([$value]);

        return $req->fetchAll();
    }

    function create(string $adresse): bool
    {
        $sql = "insert into localisation(adresse) values (?)";

        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$adresse]);
    
            return true;
        }
        catch (Exception $e) {
            die("Erreur : ". $e->getMessage() ."<br>A la ligne : ". $e->getLine());
        }
    }

    function update(int $id, string $adresse): bool
    {
        $sql = "update localisation set adresse = ? where id_localisation = ?";

        try {

            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$adresse, $id]);
    
            return true;
        }
        catch (Exception $e) {
            die("Erreur : ". $e->getMessage() ."<br>A la ligne : ". $e->getLine());
        }
    }

    function delete(int $id): bool
    {
        $sql = "delete from localisation where id_localisation = ?";

        try {

            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$id]);
    
            return true;
        }
        catch (Exception $e) {
            die("Erreur : ". $e->getMessage() ."<br>A la ligne : ". $e->getLine());
        }
    }
}

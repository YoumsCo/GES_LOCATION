<?php

namespace Models\Admin;

use Models\DBConnexion;

require_once "../../vendor/autoload.php";

use \Exception;

class Type extends DBConnexion
{
    function get_types(): array
    {
        $sql = 'select * from type';
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute();

        return $req->fetchAll();
    }

    function get_one(string $column, string $value): array
    {
        $sql = "select * from type where {$column} = ?";
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute([$value]);

        return $req->fetchAll();
    }

    function create(string $intitule, ): bool
    {
        $sql = "insert into type(intitule) values(?)";
        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$intitule]);

            return true;

        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage() . "<br>A la ligne : " . $e->getLine());
        }
    }

    function update(int $id, string $intitule): bool
    {
        $sql = "update type set intitule = ? where id_type = ?";
        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$intitule, $id]);

            return true;

        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage() . "<br>A la ligne : " . $e->getLine());
        }
    }

    function delete(int $id): bool
    {
        $sql = "delete from type where id_type = ?";
        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$id]);

            return true;

        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage() . "<br>A la ligne : " . $e->getLine());
        }
    }
}

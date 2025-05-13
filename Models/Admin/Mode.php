<?php

namespace Models\Admin;

use Models\DBConnexion;
use \Exception;

require_once '../../vendor/autoload.php';


class Mode extends DBConnexion
{
    function get_modes(): array
    {
        $sql = 'select * from mode';
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute();

        return $req->fetchAll();
    }

    function get_one(string $column, string $value): array
    {
        $sql = "select * from mode where {$column} = ?";
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute([$value]);

        return $req->fetchAll();
    }

    function create(string $libelle): bool
    {
        $sql = 'insert into mode(libelle) values(?)';
        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$libelle]);

            return true;

        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage() . "<br>A la ligne : " . $e->getLine());
        }
    }

    function update(int $id, string $libelle): bool
    {
        $sql = 'update mode set libelle = ? where id_mode = ?';
        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$libelle, $id]);

            return true;

        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage() . "<br>A la ligne : " . $e->getLine());
        }
    }

    function delete(int $id): bool
    {
        $sql = 'delete from mode where id_mode = ?';
        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$id]);

            return true;
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage() . "<br>A la ligne : " . $e->getLine());
        }
    }
}

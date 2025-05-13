<?php

namespace Models\Proprio;

use Exception;
use Models\DBConnexion;

require_once '../../vendor/autoload.php';


class Image extends DBConnexion
{
    function get_images(): array
    {
        $sql = "select * from image";
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute();

        return $req->fetchAll();
    }

    function get_one(string $column, int|string $value): array
    {
        $sql = "select * from image where {$column} = ?";
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute([$value]);

        return $req->fetchAll();
    }

    function create(int $id_bien, string $nom, int $taille, string $type): bool
    {

        $sql = "insert into image(id_bien, nom, taille, type) values(?, ?, ?, ?)";

        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$id_bien, $nom, $taille, $type]);

            return true;
        } catch (Exception $e) {
            die("Erreur !!<br>" . $e->getMessage() . "<br>A la ligne : " . $e->getLine());
        }
    }
    function delete(int $id): bool {
        $sql = "delete from image where id_image = ?";
        
        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$id]);
            return true;
        }
        catch(Exception $e) {
            die("Erreur : ". $e->getMessage() ."<br>A la ligne". $e->getLine());
        }
    }
}

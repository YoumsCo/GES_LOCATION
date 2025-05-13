<?php

namespace Models\User;

require_once '../../vendor/autoload.php';

use Models\DBConnexion;
use Models\Admin\User as AdminUser;
use \Exception;

class Location extends DBConnexion
{
    function get_all(): array
    {
        $sql = 'select * from location order by date desc';

        try {

            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute();
            return $req->fetchAll();

        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage() . '\nA la ligne : ' . $e->getLine());
        }
    }
    function get_one(string $column, string|int $value): array
    {
        $sql = "select * from location where {$column} = ?";

        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$value]);
            
            return $req->fetchAll();

        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage() . '\nA la ligne : ' . $e->getLine());
        }
    }



    function create(int $id_mode, int $id_user, int $id_bien, string $begin, string|null $deadline = null, int $montant)
    {
        try {
            if ($deadline !== null) {
                $sql = 'insert into location(id_mode, iduser, id_bien, date_debut, date_fin, montant) values (?, ?, ?, ?, ?, ?)';

                $req = DBConnexion::getConnexion()->prepare($sql);
                $req->execute([$id_mode, $id_user, $id_bien, $begin, $deadline, $montant]);

            } else {
                $sql = 'insert into location(id_mode, iduser, id_bien, date_debut, montant) values (?, ?, ?, ?, ?)';

                $req = DBConnexion::getConnexion()->prepare($sql);
                $req->execute([$id_mode, $id_user, $id_bien, $begin, $montant]);
            }

            return true;

        } catch (Exception $e) {
            die("Erreur lors du processus de location : " . $e->getMessage() . '\nA la ligne : ' . $e->getLine());
        }
    }
}

<?php

namespace Models\Admin;

require_once '../../vendor/autoload.php';

use Models\DBConnexion;
use \Exception;

class Recevoir extends DBConnexion
{

    function create(int $iduser, int $id_notif): bool
    {
        $sql = 'insert into recevoir (iduser, id_notification) values (?, ?)';

        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$iduser, $id_notif]);

            return true;
        } catch (Exception $e) {
            die("Erreur lors de la création de la notification : " . $e->getMessage() . '\nA la ligne : ' . $e->getLine());
        }
    }

    function gets(): array
    {
        $sql = 'select * from recevoir order by iduser desc';

        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute();

            return $req->fetchAll();
        } catch (Exception $e) {
            die("Erreur lors de la création de la notification : " . $e->getMessage() . '\nA la ligne : ' . $e->getLine());
        }
    }

    function procedure(int $id): array
    {
        $sql = 'CALL notifContent(?)';

        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$id]);

            return $req->fetchAll();
        } catch (Exception $e) {
            die("Erreur lors de la création de la notification : " . $e->getMessage() . '\nA la ligne : ' . $e->getLine());
        }
    }
}

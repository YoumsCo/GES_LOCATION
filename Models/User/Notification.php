<?php

namespace Models\User;

require_once '../../vendor/autoload.php';

use Models\DBConnexion;
use \Exception;

class Notification extends DBConnexion
{
    function gets(): array
    {
        $sql = 'select * from notification order by id_notification desc';

        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute();

            return $req->fetchAll();
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage() . '\nA la ligne : ' . $e->getLine());
        }
    }
}

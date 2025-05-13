<?php

namespace Models\Admin;

require_once '../../vendor/autoload.php';

use Models\DBConnexion;
use \Exception;

class Notification extends DBConnexion
{
    private Recevoir $receive;

    function __construct()
    {
        $this->receive = new Recevoir();
    }
    function create(int $iduser, string $content): bool
    {
        $sql = 'insert into notification (contenu) values (?)';

        try {
            DBConnexion::getConnexion()->beginTransaction();

            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$content]);
            $id_notif = DBConnexion::getConnexion()->lastInsertId();

            $this->receive->create($iduser, $id_notif);

            DBConnexion::getConnexion()->commit();
            return true;
        } catch (Exception $e) {
            DBConnexion::getConnexion()->rollBack();
            die("Erreur lors de la création de la notification : " . $e->getMessage() . '\nA la ligne : ' . $e->getLine());
        }
    }

    function delete(int $id): bool
    {
        $sql = 'delete from notification where id_notification  = ?';

        try {
            $req = DBConnexion::getConnexion()->prepare($sql);
            $req->execute([$id]);

            return true;
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage() . '\nA la ligne : ' . $e->getLine());
        }
    }

}

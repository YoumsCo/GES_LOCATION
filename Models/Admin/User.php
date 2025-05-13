<?php

namespace Models\Admin;

require_once '../../vendor/autoload.php';

use Models\DBConnexion;

class User extends DBConnexion
{
    function getUsers(): array
    {
        $sql = 'select * from user';
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute();

        return $req->fetchAll();
    }

    function get_one(string $column, string $value)
    {
        $sql = "select * from user where {$column} = ?";
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute([$value]);

        return $req->fetchAll();
    }

    function get_max_id(): int
    {
        $sql = "select max(iduser) from user";
        $req = DBConnexion::getConnexion()->prepare($sql);
        $req->execute();

        return $req->fetchColumn();
    }
}

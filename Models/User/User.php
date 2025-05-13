<?php

namespace Models\User;

require_once '../../vendor/autoload.php';

use Models\DBConnexion;
use Models\Admin\User as AdminUser;
use \Exception;
use Models\Admin\Notification;
use Models\Admin\Recevoir;

class User extends DBConnexion
{
    private AdminUser $users;
    private Notification $notification;

    public function __construct()
    {
        $this->users = new AdminUser();
        $this->notification = new Notification();
    }

    function checkUser(string $email): bool
    {
        $users = $this->users->getUsers();
        foreach ($users as $user) {
            if ($user['email'] == $email) {
                return true;
            }
            if ($user["role"] === "proprietaire") {
                setcookie("role", base64_encode("proprietaire"), [
                    "expires" => time() + 10 * 365 * 60 * 60 * 24,
                    "path" => "/",
                ]);
            }
        }
        return false;
    }

    function createUser(string $nom, string $email, string $password, string|null $role = null, int|null $tel, string|null $whatsapp, string $content): bool
    {
        $exists = $this->checkUser($email);
        if (!$exists) {
            $role !== null ? $sql = 'insert into user (nom, email, mot_de_passe, role, telephone, whatsapp) values (?, ?, ?, ?, ?, ?)' : $sql = 'insert into user (nom, email, mot_de_passe, telephone, whatsapp) values (?, ?, ?, ?, ?)';

            try {
                
                $req = DBConnexion::getConnexion()->prepare($sql);
                if ($role !== null) {
                    $req->execute([$nom, $email, $password, $role, $tel, $whatsapp]);
                } else {
                    $req->execute([$nom, $email, $password, $tel, $whatsapp]);
                }

                $iduser = DBConnexion::getConnexion()->lastInsertId();
                $this->notification->create($iduser, $content);

                return true;
            } catch (Exception $e) {
                DBConnexion::getConnexion()->rollBack();
                die("Erreur lors de la création de l'utilisateur : " . $e->getMessage() . '\nA la ligne : ' . $e->getLine());
            }
        } else {
            return false;
        }
    }

    function handleLogin(string $email, string $password): bool
    {
        $users = $this->users->getUsers();
        foreach ($users as $user) {
            if ($user["email"] === $email && $user["mot_de_passe"] === $password && $user["role"] === "proprietaire") {
                setcookie("role", base64_encode("proprietaire"), [
                    "expires" => time() + 10 * 365 * 60 * 60 * 24,
                    "path" => "/",
                ]);
                return true;
            } else if ($user["email"] === $email && $user["mot_de_passe"] === $password && $user["role"] !== "proprietaire") {
                return true;
            }
        }
        return false;
    }

    function login(string $email, string $password): bool
    {
        $handlelogin = $this->handleLogin($email, $password);

        return $handlelogin;
    }
}

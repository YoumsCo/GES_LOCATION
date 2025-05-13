<?php

namespace Controllers\User;

require_once '../../vendor/autoload.php';
require_once '../../Tools/tools.php';
require_once '../../Tools/Alert.php';

use Alert;
use Controllers\Admin\Mailer;
use Models\Admin\Notification;
use Models\User\User;

function checkData($option, $data)
{
    switch ($option) {
        case 'email':
            $regex = "/^[a-zA-Z]+\d{0,5}[.]{0,2}[a-zA-Z]*\d{0,5}[\@](gmail|yahoo|outlook)(\.)(com|fr)$/";
            if (preg_match_all($regex, $data)) {
                return true;
            } else {
                return false;
            }
        case 'password':
            $regex = "/^.+\d{1,}.*\W+.*$/";
            if (preg_match_all($regex, $data)) {
                return true;
            } else {
                return false;
            }
        case 'name':
            $regex = "/^[a-zA-Z]+\s?[a-zA-Z0-9]*(-|'|_)?\s?[a-zA-Z0-9]*$/";
            if (preg_match($regex, $data)) {
                return true;
            } else {
                return false;
            }
        default:
            return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        if (checkData("name", $_POST['name'])) {
            $nom = parse(ucfirst(strtolower($_POST['name'])));

            if (checkData("email", $_POST['email'])) {
                $email = parse(strtolower($_POST['email']));
                
                if (checkData("password", $_POST['password'])) {
                    $password = md5(parse($_POST['password']));
                    
                    $user = new User();

                    $content = "😉 Inscription réussie.Un email vous a été envoyé.Consultez votre boite de reception ou vos spam 😉";
                    $create = $user->createUser($nom, $email, $password, "user", null, null, $content);

                    $message = "<html><body>";
                    $message .= "<style>";
                    $message .= "p {";
                    $message .= "text-align: justify;";
                    $message .= "font-size: 13pt;";
                    $message .= "}";
                    $message .= "</style>";
                    $message .= "<p>Salut <strong>{$nom}</strong> 😊</p>";
                    $message .= "<p>Si vous avez reçu cet email c'est que tout s'est bien passé et que vous faites désormais parti de GEST_LOCATION.</p>";
                    $message .= "<p>Nous vous remercions de faire confiance à notre plateforme 😉.";
                    $message .= "<p>En cas de besoins, n'hesitez pas à contacter le service client via les liens suivants :</p>";
                    $message .= "<p><a href='mailto:youmschoco@gmail.com'>Email</a></p>";
                    $message .= "<p><a href='https://wa.me/+237690552385?text=Bonjour je suis un des clients de GEST_LOCATION et je rencontre des problèmes'>Whatsapp</a></p>";
                    $message .= "<p><a href='tel:+237690552385'>Téléphone</a></p>";
                    $message .= "<p>Ces contacts sont disponibles sur la plateforme dans le panneau latéral à gauche au cas où vous les perdez !</p>";
                    $message .= "</body></html>";

                    $mailer = new Mailer($nom, $email, "Inscription sur GEST_LOCATION", $message);
                    $alert = new Alert("Bienvenue :)", "../../Views/user/index.php");
                    $notification = new Notification();

                    if (
                        $mailer->sendMail()
                        &&
                        $alert->create_session("notification", $content)
                        &&
                        $create
                    ) {
                        setcookie("login", $email, [
                            "expires" => time() + 10 * 365 * 24 * 60 * 60,
                            "path" => "/",
                        ]);
                        $alert = new Alert("Bienvenue :)", "../../Views/user/index.php");
                        $alert->message_action($email);

                    } else {
                        $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Compte déjà existant !!", $_SERVER["HTTP_REFERER"]) : new Alert("Compte déjà existant !!", "../../Views/user/index.php");
                        $alert->show_message();
                    }
                } else {
                    $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Le mot de passe doit contenir des lettres, un ou des chiffres et enfin un ou des caratcères spéciaux !!", $_SERVER["HTTP_REFERER"]) : new Alert("Le mot de passe doit contenir des lettres, un ou des chiffres et enfin un ou des caratcères spéciaux !!", "../../Views/user/index.php");
                    $alert->show_message();
                }
            } else {
                $alert = $_SERVER["HTTP_REFERER"] ? new Alert("L\'email doit respecter le format : XXX@YYY.ZZZ", $_SERVER["HTTP_REFERER"]) : new Alert("L\'email doit respecter le format : XXX@YYY.ZZZ", "../../Views/user/index.php");
                $alert->show_message();
            }
        } else {
            $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Entrez un nom valide !!", $_SERVER["HTTP_REFERER"]) : new Alert("Entrez un nom valide !!", "../../Views/user/index.php");
            $alert->show_message();
        }
    }

    if (isset($_POST['login'])) {
        if (checkData("email", $_POST['email'])) {
            $email = parse(strtolower($_POST['email']));

            if (checkData("password", $_POST['password'])) {
                $password = md5(parse($_POST['password']));

                $user = new User();
                $login = $user->login($email, $password);
                if ($login) {
                    setcookie("login", $email, [
                        "expires" => time() + 10 * 365 * 24 * 60 * 60,
                        "path" => "/",
                    ]);

                    $message = new Alert("Bon retour :)", "../../Views/user/index.php");
                    $message->message_action($email);
                } else {
                    $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Compte introuvable. Inscrivez-vous !!", $_SERVER["HTTP_REFERER"]) : new Alert("Compte introuvable. Inscrivez-vous !!", "../../Views/user/index.php");
                    $alert->show_message();
                }
            } else {
                $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Le mot de passe doit contenir des lettres, un ou des chiffres et enfin un ou des caratcères spéciaux !!", $_SERVER["HTTP_REFERER"]) : new Alert("Le mot de passe doit contenir des lettres, un ou des chiffres et enfin un ou des caratcères spéciaux !!", "../../Views/user/index.php");
                $alert->show_message();
            }
        } else {
            $alert = $_SERVER["HTTP_REFERER"] ? new Alert("L\'email doit respecter le format : XXX@YYY.ZZZ", $_SERVER["HTTP_REFERER"]) : new Alert("L\'email doit respecter le format : XXX@YYY.ZZZ", "../../Views/user/index.php");
            $alert->show_message();
        }
    }
} else if ($_SERVER["HTTP_REFERER"]) {
    header("location:" . $_SERVER["HTTP_REFERER"]);
} else {
    header("location: ../../Views/user/index.php");
}

<?php

use Models\Admin\Localisation;

require_once '../../vendor/autoload.php';
require_once '../../tools/Alert.php';
require_once '../../tools/tools.php';

function checkData(string $option, string|int $data)
{
    switch ($option) {
        case 'adresse':
            $regex = "/^[a-zA-Z]+\d{0,5}[.]{0,2}[a-zA-Z]*\d{0,5}[\@](gmail|yahoo|outlook)(\.)(com|fr)$/";
            if (preg_match($regex, subject: $data)) {
                return true;
            } else {
                return false;
            }
        default:
            return false;
    }
}

if (isset($_POST['send']) && strpos(strtolower($_SERVER["HTTP_REFERER"]), strtolower("Localisation/add"))) {

    if (isset($_POST["intitule"])) {
        $intitule = ucfirst(strtolower(parse($_POST["intitule"])));

        $location = new Localisation();
        if ($location->create($intitule)) {
            $alert = new Alert("Insertion effectuée :)", "../../Views/Localisation/index.php");
            $alert->show_message();
        } else {
            $alert = new Alert("Une erreur s\'est produite :(", $_SERVER['HTTP_REFERER']);
            $alert->show_message();
        }
    } else {
        $alert = new Alert("Une erreur s\'est produite :(", $_SERVER['HTTP_REFERER']);
        $alert->show_message();
    }

} else if (isset($_GET['token']) && strpos(strtolower($_SERVER["HTTP_REFERER"]), strtolower("Localisation/index"))) {

    $id = base64_decode($_GET["token"]);
    $location = new Localisation();

    if ($location->delete($id)) {
        $alert = new Alert("Suppression effectuée :)", "../../Views/Localisation/index.php");
        $alert->show_message();
    } else {
        $alert = new Alert("Une erreur s\'est produite :(", $_SERVER['HTTP_REFERER']);
        $alert->show_message();
    }
} else if (isset($_POST['send']) && strpos(strtolower($_SERVER["HTTP_REFERER"]), strtolower("Localisation/update"))) {
    if (isset($_POST['token']) && is_numeric(base64_decode($_POST["token"]))) {
        $id = base64_decode($_POST["token"]);

        if (isset($_POST["intitule"])) {
            $intitule = ucfirst(strtolower(parse($_POST["intitule"])));

            $location = new Localisation();
            if ($location->update($id, $intitule)) {
                $alert = new Alert("Modification effectuée :)", "../../Views/Localisation/index.php");
                $alert->show_message();
            } else {
                $alert = new Alert("Une erreur s\'est produite :(", $_SERVER['HTTP_REFERER']);
                $alert->show_message();
            }
        } else {
            $alert = new Alert("Une erreur s\'est produite :(", $_SERVER['HTTP_REFERER']);
            $alert->show_message();
        }
    } else {
        header('location:../../Views/user/index.php');
    }
    
    
} else {
    header('location:../../Views/user/index.php');
}

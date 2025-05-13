<?php

use Models\Admin\Type;

require_once '../../vendor/autoload.php';
require_once '../../tools/Alert.php';
require_once '../../tools/tools.php';

function checkData(string $option, string|int $data)
{
    switch ($option) {
        case 'name':
            $regex = "/^[a-zA-Z]{3,30}$/";
            if (preg_match($regex, subject: $data)) {
                return true;
            } else {
                return false;
            }
        default:
            return false;
    }
}

if (isset($_POST['send']) && strpos(strtolower($_SERVER["HTTP_REFERER"]), strtolower("Type/add"))) {

    if (isset($_POST["intitule"]) && checkData("name", $_POST["intitule"])) {
        $intitule = ucfirst(strtolower(parse($_POST["intitule"])));

        $types = new Type();
        if ($types->create($intitule)) {
            $alert = new Alert("Insertion effectuée :)", "../../Views/Type/index.php");
            $alert->show_message();
        } else {
            $alert = new Alert("Une erreur s\'est produite :(", $_SERVER['HTTP_REFERER']);
            $alert->show_message();
        }
    } else {
        $alert = new Alert("Une erreur s\'est produite :(", $_SERVER['HTTP_REFERER']);
        $alert->show_message();
    }

} else if (isset($_GET['token']) && strpos(strtolower($_SERVER["HTTP_REFERER"]), strtolower("Type/index"))) {

    $id = base64_decode($_GET["token"]);
    $types = new Type();

    if ($types->delete($id)) {
        $alert = new Alert("Suppression effectuée :)", "../../Views/Type/index.php");
        $alert->show_message();
    } else {
        $alert = new Alert("Une erreur s\'est produite :(", $_SERVER['HTTP_REFERER']);
        $alert->show_message();
    }
} else if (isset($_POST['send']) && strpos(strtolower($_SERVER["HTTP_REFERER"]), strtolower("Type/update"))) {
    if (isset($_POST['token']) && is_numeric(base64_decode($_POST["token"]))) {
        $id = base64_decode($_POST["token"]);

        if (isset($_POST["intitule"]) && checkData("name", $_POST["intitule"])) {
            $intitule = ucfirst(strtolower(parse($_POST["intitule"])));

            $types = new Type();
            if ($types->update($id, $intitule)) {
                $alert = new Alert("Modification effectuée :)", "../../Views/Type/index.php");
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

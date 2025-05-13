<?php

session_start();

use Controllers\Proprio\Controller;
use Models\Admin\BienImmobilier;
use Models\Proprio\Image;

require_once "../../Tools/tools.php";
require_once "../../Tools/Alert.php";
require_once "../../vendor/autoload.php";

function countDir(string $rep): int
{
    $dir = scandir($rep);

    return ceil(sqrt(count($dir) - 2));
}

$biens = new BienImmobilier();
$images = new Image();
$houses = new BienImmobilier();
$files = new Controller();

if (
    isset($_GET["token"])
    &&
    strpos($_SERVER["HTTP_REFERER"], "Views/proprio/index.php")
    &&
    is_numeric(base64_decode($_GET["token"]))
    &&
    !empty($biens->get_one("id_bien", parse(base64_decode($_GET["token"]))))
) {
    $token = parse(base64_decode($_GET["token"]));

    $rep = "../../houses/";

    if (is_numeric($token) && $token > countDir("../../Don't_Touch")) {

        if (
            is_array($images->get_one("id_bien", $token))
            &&
            !empty($images->get_one("id_bien", $token))
            &&
            is_array($houses->get_one("id_bien", $token))
            &&
            !empty($houses->get_one("id_bien", $token))
            &&
            is_array($files->get_images($rep, $_COOKIE["login"]))
            &&
            !empty($files->get_images($rep, $_COOKIE["login"]))
            &&
            isset($_COOKIE["login"])
        ) {
            foreach ($files->get_images($rep, $_COOKIE["login"]) as $file) {
                [$file_email, $file_time, $file_other] = explode("_", $file);
                [$file_id, $file_ext] = explode(".", $file_other);

                if (!empty($images->get_one("id_bien", $token))) {
                    foreach ($images->get_one("id_bien", $token) as $image) {
                        $id_image = $image["id_image"];
                    }
                } else {
                    header("location:../../Views/user/index.php");
                    break;
                }

                if ($images->delete($id_image)) {
                    $_SESSION["return"] = true;

                    if ($token === $file_id) {

                        if (unlink((string) $rep . $file)) {
                            true;
                        } else {
                            $alert = $_SERVER["HTTP_REFERER"]
                                ?
                                new Alert("Une erreur est survenue !", $_SERVER["HTTP_REFERER"])
                                :
                                new Alert("Une erreur est survenue !", "../../Views/user/index.php");

                            $alert->show_message();
                            return;
                        }
                    }

                } else {
                    $alert = $_SERVER["HTTP_REFERER"]
                        ?
                        new Alert("Une erreur est survenue :(", $_SERVER["HTTP_REFERER"])
                        :
                        new Alert("Une erreur est survenue :(", "../../Views/user/index.php");

                    $alert->show_message();
                    return;
                }
            }

            if (isset($_SESSION["return"])) {
                foreach ($houses->get_one("id_bien", $token) as $house) {
                    $id_bien = $house["id_bien"];
                }

                if ($houses->delete($id_bien)) {
                    $alert = $_SERVER["HTTP_REFERER"]
                        ?
                        new Alert("Suppréssion effectuée :)", $_SERVER["HTTP_REFERER"])
                        :
                        new Alert("Suppréssion effectuée :)", "../../Views/user/index.php");

                    $alert->show_message();
                    session_destroy();
                    session_unset();
                }

            }
        } else {
            header("location:../../Views/user/index.php");
        }

    } else if (!is_numeric($token)) {
        header("location:../../Views/user/index.php");
    } else {
        $alert = $_SERVER["HTTP_REFERER"]
            ?
            new Alert("Ce logement ne peut pas etre supprimé !", $_SERVER["HTTP_REFERER"])
            :
            new Alert("Ce logement ne peut pas etre supprimé !", "../../Views/user/index.php");

        $alert->show_message();
    }
} else {
    header("location:../../Views/user/index.php");
}
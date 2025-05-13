<?php

use Models\Admin\User;


require_once '../../vendor/autoload.php';
require_once '../../tools/Alert.php';
require_once '../../tools/tools.php';
require_once './Controller.php';

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
        case 'email':
            $regex = "/^[a-zA-Z]+\d{0,5}[.]{0,2}[a-zA-Z]*\d{0,5}[\@](gmail|yahoo|outlook)(\.)(com|fr)$/";
            if (preg_match_all($regex, $data)) {
                return true;
            } else {
                return false;
            }
        case 'prix':
            $regex = "/^[1-9]\d{4,5}$/";
            if (preg_match_all($regex, $data)) {
                return true;
            } else {
                return false;
            }
        case 'location':
            $regex = "/^[a-zA-Z]+\s?[a-zA-Z0-9]*(-|'|_)?\s?[a-zA-Z0-9]*$/";
            if (preg_match_all($regex, $data)) {
                return true;
            } else {
                return false;
            }
        case 'type':
            $regex = "/^[a-zA-Z]{4,20}$/";
            if (preg_match_all($regex, $data)) {
                return true;
            } else {
                return false;
            }
        default:
            return false;
    }
}

function checkSize(array $files): bool
{
    $size = $files['size'];
    if ($size > 2000000) {
        return true;
    }

    return false;
}

function checkError(array $files): bool
{
    $error = $files['error'];
    if ($error !== 0) {
        return true;
    }

    return false;
}

function checkType(array $files): bool
{
    $types = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
    $type = $files['type'];
    if (!in_array($type, $types)) {
        return true;
    }
    return false;
}

if (isset($_POST["send"]) && strpos(strtolower($_SERVER["HTTP_REFERER"]), strtolower("profile"))) {
    if (isset($_FILES['file']) && !checkError($_FILES['file'])) {
        if (!checkSize($_FILES['file'])) {
            $file_name = $_FILES["file"]["name"];
            $file_tmp = $_FILES["file"]["tmp_name"];

            $user = new User();
            if (isset($_COOKIE["login"]) && !empty($user->get_one("email", parse($_COOKIE["login"])))) {

                foreach ($user->get_one("email", parse($_COOKIE["login"])) as $data) {
                    $email = $data["email"];
                    $iduser = $data["iduser"];
                }

                [$name, $ext] = explode(".", $file_name);
                $new_name = (string) $email . "_" . time() . "_" . $iduser . "." . $ext;
                $rep = "../../profiles/";

                if (!is_dir($rep)) {
                    mkdir($rep, 0744, true);
                }

                $new_tmp = (string) $rep . $new_name;
                try {
                    if(!empty(get_profiles($rep, $email, $iduser))) {
                        foreach(get_profiles($rep, $email, $iduser) as $file) {
                            unlink((string) $rep.$file);
                            sleep(.1);
                        }
                    }
                    if(move_uploaded_file($file_tmp, $new_tmp)) {
                        $alert = new Alert("Profile ajouté 😉", $_SERVER['HTTP_REFERER']);
                        $alert->show_message();
                    }
                    else {
                        $alert = new Alert("Une erreur d'est produite ):", $_SERVER['HTTP_REFERER']);
                        $alert->show_message();
                    }
                } catch (Exception $e) {
                    $alert = new Alert("Une erreur d'est produite ):", $_SERVER['HTTP_REFERER']);
                    $alert->show_message();
                }

            } else {
                header('location:../../Views/user/index.php');
            }

        } else {
            $alert = new Alert('Fichier trop volumineux détecté ):', $_SERVER['HTTP_REFERER']);
            $alert->show_message();
        }

    } else {
        header('location:../../Views/user/index.php');
    }

} else {
    header('location:../../Views/user/index.php');
}


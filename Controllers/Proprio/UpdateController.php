<?php

use Controllers\Proprio\Controller;

session_start();

use Models\Admin\BienImmobilier;
use Models\Admin\Localisation;
use Models\Admin\Type;
use Models\Admin\User;
use Models\Proprio\Image;

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
    foreach ($files['size'] as $key => $value) {
        $size = $files['size'][$key];
        if ($size > 5000000) {
            return true;
        }
    }

    return false;
}

function checkError(array $files): bool
{
    foreach ($files['error'] as $key => $value) {
        $error = $files['error'][$key];
        if ($error !== 0) {
            return true;
        }
    }

    return false;
}

function checkLength(array $files): bool
{
    if (sizeof($files) >= 5 && sizeof($files) <= 10) {
        return true;
    }

    return false;
}

function checkType(array $files): bool
{
    $types = ['image/png', 'image/jpeg', 'image/jpg'];
    foreach ($files['type'] as $key => $value) {
        $type = $files['type'][$key];
        if (!in_array($type, $types)) {
            return true;
        }
    }
    return false;
}

function checkPostType(string $value): bool
{
    $tab = [];
    $types = new Type();
    foreach ($types->get_types() as $key) {
        array_push($tab, $value);
    }
    if (!in_array($value, $tab)) {
        return true;
    }
    return false;
}

if (isset($_POST['send']) && isset($_POST["token"]) && is_numeric(base64_decode($_POST["token"]))) {

    if (isset($_FILES['files']) && !checkError($_FILES['files'])) {

        if (checkLength($_FILES['files']['name'])) {

            if (!checkSize($_FILES['files'])) {

                if (!checkType(files: $_FILES['files'])) {
                    $user = new User();
                    $types = new Type();
                    $locations = new Localisation();
                    $biens = new BienImmobilier();
                    $images = new Image();

                    if ($_POST['info']) {
                        $info = ucfirst(strtolower(parse($_POST['info'])));

                        if ($_POST['prix'] && checkData('prix', $_POST['prix'])) {
                            $prix = parse($_POST['prix']);

                            if ($_POST['location'] && checkData('location', $_POST['location'])) {
                                foreach ($locations->get_localisation() as $location) {
                                    if ($location['adresse'] !== $_POST['location']) {
                                        header('location:../../Views/user/index.php');
                                        sleep(60);
                                    }
                                }

                                if ($_POST['type'] && checkData('type', $_POST['type']) && !checkPostType($_POST['type'])) {

                                    if (isset($_COOKIE['login']) && !empty($user->get_one('email', $_COOKIE['login']))) {

                                        foreach ($user->get_one('email', $_COOKIE['login']) as $value) {
                                            $iduser = $value['iduser'];
                                            $role = $value['role'];

                                            if ($role !== 'proprietaire') {
                                                header('location:../../Views/user/index.php');
                                            }
                                        }

                                        foreach ($types->get_one('intitule', $_POST['type']) as $value) {
                                            $id_type = $value['id_type'];
                                        }
                                        foreach ($locations->get_one('adresse', $_POST['location']) as $value) {
                                            $id_localisation = $value['id_localisation'];
                                        }

                                        if (isset($_COOKIE['role']) && base64_decode($_COOKIE['role']) === 'proprietaire') {

                                            $matricule = $biens->get_max('id_bien');

                                            if ($_POST['nom']) {
                                                if (checkData('name', $_POST['nom'])) {
                                                    $name = ucfirst(strtolower($_POST['nom']));

                                                    if ($biens->update($id_localisation, $iduser, $id_type, $name, $info, $prix, base64_decode($_POST["token"]))) {
                                                        $_SESSION['returnValue'] = "ok";
                                                    } else {
                                                        $alert = new Alert('Une erreur est survenue ):', $_SERVER['HTTP_REFERER']);
                                                        $alert->show_message();
                                                        return;
                                                    }
                                                } else {
                                                    $alert = new Alert("⚠️ Le nom ne doit contenir  que des lettres et pas d\'espaces ⚠️", $_SERVER['HTTP_REFERER']);
                                                    $alert->show_message();
                                                }
                                            } else {
                                                if ($biens->update($id_localisation, $iduser, $id_type, null, $info, $prix, base64_decode($_POST["token"]))) {
                                                    $_SESSION['returnValue'] = 'ok';
                                                } else {
                                                    $alert = new Alert('Une erreur est survenue ):', $_SERVER['HTTP_REFERER']);
                                                    $alert->show_message();
                                                    return;
                                                }
                                            }

                                            if (isset($_SESSION['returnValue']) && $_SESSION['returnValue'] === 'ok') {
                                                $controller = new Controller();
                                                $rep = "../../houses/";
                                                if (!empty($controller->get_images($rep, $_COOKIE["login"]))) {

                                                    foreach ($controller->get_images($rep, $_COOKIE["login"]) as $file) {

                                                        [$file_email, $file_time, $file_other] = explode("_", $file);
                                                        [$file_id, $file_ext] = explode(".", $file_other);
                                                        if ($file_id === base64_decode($_POST["token"])) {
                                                            if (unlink((string) $rep . $file)) {
                                                                $_SESSION["delete"] = true;
                                                            } else {
                                                                $alert = new Alert("Une erreur s\'est produite ):", $_SERVER['HTTP_REFERER']);
                                                                $alert->show_message();
                                                                break;
                                                            }
                                                        }
                                                    }

                                                    if (isset($_SESSION["delete"]) && $_SESSION["delete"]) {

                                                        foreach ($_FILES['files']['name'] as $key => $value) {
                                                            $file_size = $_FILES['files']['size'][$key];
                                                            $file_type = $_FILES['files']['type'][$key];
                                                            [$file_name, $file_ext] = explode('.', $_FILES['files']['name'][$key]);
                                                            $tmp = $_FILES['files']['tmp_name'][$key];

                                                            $new_name = $_COOKIE['login'] . '_' . time() + 1 . '_' . base64_decode($_POST['token']);
                                                            $new_rep = '../../houses/';

                                                            if (!is_dir($new_rep)) {
                                                                mkdir($new_rep, 0744, true);
                                                            }

                                                            $new_tmp = (string) $new_rep . $new_name . '.' . $file_ext;

                                                            if ($images->create(base64_decode($_POST["token"]), $file_name, $file_size, $file_type) && move_uploaded_file($tmp, $new_tmp)) {
                                                                $_SESSION['finally'] = true;
                                                                sleep(1.5);
                                                            } else {
                                                                $alert = new Alert("Une erreur s\'est produite ):", $_SERVER['HTTP_REFERER']);
                                                                $alert->show_message();
                                                            }
                                                        }

                                                        if (isset($_SESSION['finally']) && $_SESSION['finally'] === true) {
                                                            $alert = new Alert('Modification(s) effectuée(s) :)', '../../Views/proprio/index.php');
                                                            $alert->show_message();
                                                        } else {
                                                            $alert = new Alert("Une erreur s\'est produite :(", $_SERVER['HTTP_REFERER']);
                                                            $alert->show_message();
                                                        }
                                                        session_destroy();
                                                        session_unset();
                                                    }

                                                } else {
                                                    $alert = new Alert("Une erreur s\'est produite ICI ):", $_SERVER['HTTP_REFERER']);
                                                    $alert->show_message();
                                                }

                                            } else {
                                                $alert = new Alert("Une erreur s\'est produite ICI ):", $_SERVER['HTTP_REFERER']);
                                                $alert->show_message();
                                            }

                                        } else {
                                            header('location:../../Views/user/index.php');
                                        }
                                    } else {
                                        header('location:../../Views/user/index.php');
                                    }

                                } else {
                                    $alert = new Alert('Le type est invalide ):', $_SERVER['HTTP_REFERER']);
                                    $alert->show_message();
                                }

                            } else {
                                $alert = new Alert('La description est obligatoire ):', $_SERVER['HTTP_REFERER']);
                                $alert->show_message();
                            }

                        } else {
                            $alert = new Alert('Prix invalide ):', $_SERVER['HTTP_REFERER']);
                            $alert->show_message();
                        }

                    } else {
                        $alert = new Alert('La description est obligatoire ):', $_SERVER['HTTP_REFERER']);
                        $alert->show_message();
                    }

                } else {
                    $alert = new Alert('Type invalide ):', $_SERVER['HTTP_REFERER']);
                    $alert->show_message();
                }

            } else {
                $alert = new Alert('Fichier trop volumineux détecté ):', $_SERVER['HTTP_REFERER']);
                $alert->show_message();
            }

        } else {
            $alert = new Alert("⚠️ L\'interval de fichiers est de 5 à 10 ⚠️", $_SERVER['HTTP_REFERER']);
            $alert->show_message();
        }

    } else {
        header('location:../../Views/user/index.php');
    }

} else {
    header('location:../../Views/user/index.php');
}

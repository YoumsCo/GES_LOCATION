<?php

require_once '../../vendor/autoload.php';
require_once '../../Tools/tools.php';
require_once '../Admin/Mailer.php';
require_once '../../Tools/Alert.php';

use \Controllers\Admin\Mailer;
use Models\Admin\BienImmobilier;
use Models\Admin\Localisation;
use Models\Admin\Mode;
use Models\Admin\Notification;
use Models\Admin\Type;
use Models\Admin\User as AdminUser;
use Models\User\Location;

function checkData($option, $data)
{
    switch ($option) {
        case 'name':
            $regex = "/^[a-zA-Z]+\s?[a-zA-Z0-9]*(-|'|_)?\s?[a-zA-Z0-9]*$/";
            if (preg_match($regex, $data)) {
                return true;
            } else {
                return false;
            }
        case 'number':
            $regex = "/^\d{1,7}$/";
            if (preg_match($regex, $data)) {
                return true;
            } else {
                return false;
            }
        case 'location':
            $location = new Localisation();
            $locations = $location->get_localisation();
            foreach ($locations as $value) {
                if ($data === $value["adresse"]) {
                    return true;
                }
            }
        case 'type':
            $type = new Type();
            $types = $type->get_types();
            foreach ($types as $value) {
                if ($data === $value["intitule"]) {
                    return true;
                }
            }
        case 'mode':
            $mode = new Mode();
            $modes = $mode->get_modes();
            foreach ($modes as $value) {
                if ($data === $value["libelle"]) {
                    return true;
                }
            }
        case 'date':
            $regex = "/^\d{2}[-]\d{2}[-]\d{4}$/";
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

    if (strpos($_SERVER["HTTP_REFERER"], "show_one")) {
        if (isset($_POST["name"]) && !empty($_POST["name"]) && checkData("name", $_POST["name"]) && strlen($_POST["name"]) >= 3 && strlen($_POST["name"]) <= 30) {
            $name = ucfirst(strtolower(parse($_POST["name"])));

            if (isset($_POST["name_p"]) && !empty($_POST["name_p"]) && checkData("name", $_POST["name_p"]) && strlen($_POST["name_p"]) >= 3 && strlen($_POST["name_p"]) <= 30) {
                $name_p = ucfirst(strtolower(parse($_POST["name_p"])));
                $user = new AdminUser();
                foreach ($user->get_one("email", $_COOKIE["login"]) as $value) {
                    $role = $value["role"];
                }
                if ($role === "admin") {
                    $alert = $_SERVER["HTTP_REFERER"] ? new Alert("L\'administrateur ne peut pas effectuer une location !!", $_SERVER["HTTP_REFERER"]) : new Alert("L\'administrateur ne peut pas effectuer une location !!", "../../Views/user/index.php");
                    $alert->show_message();
                } else if ($name !== $name_p) {

                    if (isset($_POST["price"]) && !empty($_POST["price"]) && checkData("number", $_POST["price"])) {
                        $price = parse($_POST["price"]);

                        if (isset($_POST["location"]) && !empty($_POST["location"]) && checkData("location", $_POST["location"])) {
                            $location = parse($_POST["location"]);

                            if (isset($_POST["type"]) && !empty($_POST["type"]) && checkData("type", $_POST["type"])) {
                                $type = parse($_POST["type"]);

                                if (isset($_POST["mode"]) && !empty($_POST["mode"]) && checkData("mode", $_POST["mode"])) {
                                    $mode = parse($_POST["mode"]);

                                    if (isset($_POST["begin"]) && !empty($_POST["begin"]) && checkData("date", $_POST["begin"])) {
                                        list($year, $month, $date) = explode("-", parse($_POST["begin"]));
                                        $begin = $date . "-" . $month . "-" . $year;

                                        if (isset($_POST["deadline"]) && !empty($_POST["deadline"]) && checkData("date", $_POST["deadline"])) {
                                            list($year, $month, $date) = explode("-", parse($_POST["deadline"]));
                                            $deadline = $date . "-" . $month . "-" . $year;

                                            list($beginYear, $beginMonth, $beginDate) = explode("-", $begin);
                                            list($deadYear, $deadMonth, $deadDate) = explode("-", $deadline);

                                            if ($beginYear === $deadYear && $beginMonth === $deadMonth && $beginDate > $deadDate) {

                                                $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Veuillez entrer une date de fin valide !!", $_SERVER["HTTP_REFERER"]) : new Alert("Veuillez entrer une date de fin valide !!", "../../Views/user/index.php");
                                                $alert->show_message();

                                            } else if ($beginYear === $deadYear && $beginMonth > $deadMonth) {

                                                $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Veuillez entrer une date de fin valide !!", $_SERVER["HTTP_REFERER"]) : new Alert("Veuillez entrer une date de fin valide !!", "../../Views/user/index.php");
                                                $alert->show_message();

                                            } else if ($_COOKIE["login"] && !empty($_COOKIE["login"]) && $_POST["matricule"] && !empty($_POST["matricule"])) {
                                                $user = new AdminUser();
                                                $bien = new BienImmobilier();
                                                $modeObject = new Mode();

                                                $user_datas = $user->get_one("email", $_COOKIE["login"]);
                                                $bien_datas = $bien->get_one("matricule", parse($_POST["matricule"]));
                                                $mode_datas = $modeObject->get_one("libelle", $mode);

                                                foreach ($user_datas as $value) {
                                                    $id_user = $value["iduser"];
                                                    $user_email = $value["email"];
                                                }
                                                foreach ($bien_datas as $value) {
                                                    $id_bien = $value["id_bien"];
                                                    $id_user_in_bien = $value["iduser"];

                                                    foreach ($user->get_one("iduser", $id_user_in_bien) as $value) {
                                                        $id_user_p = $value["iduser"];
                                                        $user_email_p = $value["email"];
                                                    }
                                                }
                                                foreach ($mode_datas as $value) {
                                                    $id_mode = $value["id_mode"];
                                                }

                                                if (is_numeric($id_user) && is_numeric($id_bien) && is_numeric($id_mode)) {

                                                    $location = new Location();

                                                    $create = $location->create($id_mode, $id_user, $id_bien, $begin, $deadline, $price);

                                                    $message = "<html><body>";
                                                    $message .= "<style>";
                                                    $message .= "p {";
                                                    $message .= "text-align: justify;";
                                                    $message .= "font-size: 13pt;";
                                                    $message .= "}";
                                                    $message .= "strong {";
                                                    $message .= "font-weight: 900;";
                                                    $message .= "}";
                                                    $message .= "</style>";
                                                    $message .= "<p>Salut {$name} 😊</p>";
                                                    $message .= "<p>Votre location a été effectuée avec succès.</p>";
                                                    $message .= "<p>Nous vous remercions de faire confiance à notre plateforme 😉.";
                                                    $message .= "<p>En cas de besoins, n'hesitez pas à contacter le service client via les liens suivants :</p>";
                                                    $message .= "<p><a href='mailto:youmschoco@gmail.com'>Email</a></p>";
                                                    $message .= "<p><a href='https://wa.me/+237690552385?text=Bonjour je suis un des clients de GEST_LOCATION et je rencontre des problèmes'>Whatsapp</a></p>";
                                                    $message .= "<p><a href='tel:+237690552385'>Téléphone</a></p>";
                                                    $message .= "<p>Ces contacts sont disponibles sur la plateforme dans le panneau latéral à gauche au cas où vous les perdez !</p>";
                                                    $message .= "</body></html>";

                                                    $message_p = "<html><body>";
                                                    $message_p .= "<style>";
                                                    $message_p .= "p {";
                                                    $message_p .= "text-align: justify;";
                                                    $message_p .= "font-size: 13pt;";
                                                    $message_p .= "}";
                                                    $message_p .= "strong {";
                                                    $message_p .= "font-weight: 900;";
                                                    $message_p .= "}";
                                                    $message_p .= "</style>";
                                                    $message_p .= "<p>Salut {$name_p} 😊</p>";
                                                    $message_p .= "<p>🎉Félicitation 🎉.</p>";
                                                    $message_p .= "<p>L'un de vos logements vient d'etre pris par {$name}.</p>";
                                                    $message_p .= "<p>Nous vous remercions de faire confiance à notre plateforme 😉.";
                                                    $message_p .= "<p>En cas de besoins, n'hesitez pas à contacter le service client via les liens suivants :</p>";
                                                    $message_p .= "<p><a href='mailto:youmschoco@gmail.com'>Email</a></p>";
                                                    $message_p .= "<p><a href='https://wa.me/+237690552385?text=Bonjour je suis un des clients de GEST_LOCATION et je rencontre des problèmes'>Whatsapp</a></p>";
                                                    $message_p .= "<p><a href='tel:+237690552385'>Téléphone</a></p>";
                                                    $message_p .= "<p>Ces contacts sont disponibles sur la plateforme dans le panneau latéral à gauche au cas où vous les perdez !</p>";
                                                    $message_p .= "</body></html>";

                                                    $mailer = new Mailer($name, $user_email, "Confirmation de la location", $message);
                                                    $mailer_p = new Mailer($name, $user_email_p, "Processus de location", $message_p);

                                                    $alert = new Alert("Location effectuée avec succès", null);
                                                    $notification = new Notification();
                                                    $notification_p = new Notification();
                                                    $content = "🎉 Votre location a été effectuée avec succès.Consultez vos email ou vos spam pour plus d\'informations 🎉";
                                                    $content_p = "🎉 Félicitation l'un de vos logements vient d'etre pris par l'utilisateur {$name}. Voici son email : {$user_email} 🎉";

                                                    if (
                                                        $mailer->sendMail()
                                                        &&
                                                        $mailer_p->sendMail()
                                                        &&
                                                        $notification->create($id_user, $content)
                                                        &&
                                                        $notification_p->create($id_user_p, $content_p)
                                                        &&
                                                        $create
                                                        &&
                                                        $bien->updateForReservation($id_bien)
                                                        &&
                                                        $alert->create_session("notification", $content)
                                                    ) {
                                                        $alert = new Alert($content, "../../Views/user/index.php");
                                                        $alert->show_message();

                                                    } else {
                                                        $alert = new Alert("Une erreur s'est produite.", "../../Views/user/index.php");
                                                        $alert->create_session("notification");
                                                        $alert->show_message();
                                                    }

                                                } else if ($_SERVER["HTTP_REFERER"]) {
                                                    header("location:" . $_SERVER["HTTP_REFERER"]);
                                                } else {
                                                    header("location: ../../Views/user/index.php");
                                                }
                                            } else if ($_SERVER["HTTP_REFERER"]) {
                                                header("location:" . $_SERVER["HTTP_REFERER"]);
                                            } else {
                                                header("location: ../../Views/user/index.php");
                                            }

                                        } else if ($_COOKIE["login"] && !empty($_COOKIE["login"]) && $_POST["matricule"] && !empty($_POST["matricule"])) {

                                            $user = new AdminUser();
                                            $bien = new BienImmobilier();
                                            $modeObject = new Mode();

                                            $user_datas = $user->get_one("email", $_COOKIE["login"]);
                                            $bien_datas = $bien->get_one("matricule", parse($_POST["matricule"]));
                                            $mode_datas = $modeObject->get_one("libelle", $mode);

                                            foreach ($user_datas as $value) {
                                                $id_user = $value["iduser"];
                                                $user_email = $value["email"];
                                            }
                                            foreach ($bien_datas as $value) {
                                                $id_bien = $value["id_bien"];
                                                $id_user_in_bien = $value["iduser"];

                                                foreach ($user->get_one("iduser", $id_user_in_bien) as $value) {
                                                    $id_user_p = $value["iduser"];
                                                    $user_email_p = $value["email"];
                                                }
                                            }
                                            foreach ($mode_datas as $value) {
                                                $id_mode = $value["id_mode"];
                                            }

                                            if (is_numeric($id_user) && is_numeric($id_bien) && is_numeric($id_mode)) {

                                                $location = new Location();

                                                $create = $location->create($id_mode, $id_user, $id_bien, $begin, null, $price);

                                                $message = "<html><body>";
                                                $message .= "<style>";
                                                $message .= "p {";
                                                $message .= "text-align: justify;";
                                                $message .= "font-size: 13pt;";
                                                $message .= "}";
                                                $message .= "strong {";
                                                $message .= "font-weight: 900;";
                                                $message .= "}";
                                                $message .= "</style>";
                                                $message .= "<p>Salut {$name} 😊</p>";
                                                $message .= "<p>Votre location a été effectuée avec succès.</p>";
                                                $message .= "<p>Nous vous remercions de faire confiance à notre plateforme 😉.";
                                                $message .= "<p>En cas de besoins, n'hesitez pas à contacter le service client via les liens suivants :</p>";
                                                $message .= "<p><a href='mailto:youmschoco@gmail.com'>Email</a></p>";
                                                $message .= "<p><a href='https://wa.me/+237690552385?text=Bonjour je suis un des clients de GEST_LOCATION et je rencontre des problèmes'>Whatsapp</a></p>";
                                                $message .= "<p><a href='tel:+237690552385'>Téléphone</a></p>";
                                                $message .= "<p>Ces contacts sont disponibles sur la plateforme dans le panneau latéral à gauche au cas où vous les perdez !</p>";
                                                $message .= "</body></html>";

                                                $message_p = "<html><body>";
                                                $message_p .= "<style>";
                                                $message_p .= "p {";
                                                $message_p .= "text-align: justify;";
                                                $message_p .= "font-size: 13pt;";
                                                $message_p .= "}";
                                                $message_p .= "strong {";
                                                $message_p .= "font-weight: 900;";
                                                $message_p .= "}";
                                                $message_p .= "</style>";
                                                $message_p .= "<p>Salut {$name_p} 😊</p>";
                                                $message_p .= "<p>🎉Félicitation 🎉.</p>";
                                                $message_p .= "<p>L'un de vos logements vient d'etre pris par {$name}.</p>";
                                                $message_p .= "<p>Nous vous remercions de faire confiance à notre plateforme 😉.";
                                                $message_p .= "<p>En cas de besoins, n'hesitez pas à contacter le service client via les liens suivants :</p>";
                                                $message_p .= "<p><a href='mailto:youmschoco@gmail.com'>Email</a></p>";
                                                $message_p .= "<p><a href='https://wa.me/+237690552385?text=Bonjour je suis un des clients de GEST_LOCATION et je rencontre des problèmes'>Whatsapp</a></p>";
                                                $message_p .= "<p><a href='tel:+237690552385'>Téléphone</a></p>";
                                                $message_p .= "<p>Ces contacts sont disponibles sur la plateforme dans le panneau latéral à gauche au cas où vous les perdez !</p>";
                                                $message_p .= "</body></html>";

                                                $mailer = new Mailer($name, $user_email, "Confirmation de la location", $message);
                                                $mailer_p = new Mailer($name, $user_email_p, "Processus de location", $message_p);

                                                $alert = new Alert("Location effectuée avec succès", null);
                                                $notification = new Notification();
                                                $notification_p = new Notification();
                                                $content = "🎉 Votre location a été effectuée avec succès.Consultez vos email ou vos spam pour plus d\'informations 🎉";
                                                $content_p = "🎉 Félicitation l'un de vos logements vient d'etre pris par l'utilisateur {$name}. Voici son email : {$user_email} 🎉";

                                                if (
                                                    $mailer->sendMail()
                                                    &&
                                                    $mailer_p->sendMail()
                                                    &&
                                                    $notification->create($id_user, $content)
                                                    &&
                                                    $notification_p->create($id_user_p, $content_p)
                                                    &&
                                                    $create
                                                    &&
                                                    $bien->updateForReservation($id_bien)
                                                    &&
                                                    $alert->create_session("notification", $content)
                                                ) {
                                                    $alert = new Alert($content, "../../Views/user/index.php");
                                                    $alert->show_message();

                                                } else {
                                                    $alert = new Alert("Une erreur s'est produite.", "../../Views/user/index.php");
                                                    $alert->create_session("notification");
                                                    $alert->show_message();
                                                }

                                            } else if ($_SERVER["HTTP_REFERER"]) {
                                                header("location:" . $_SERVER["HTTP_REFERER"]);
                                            } else {
                                                header("location: ../../Views/user/index.php");
                                            }
                                        } else if ($_SERVER["HTTP_REFERER"]) {
                                            header("location:" . $_SERVER["HTTP_REFERER"]);
                                        } else {
                                            header("location: ../../Views/user/index.php");
                                        }

                                    } else {
                                        $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Veuillez renseigner la date de debut de votre location !!", $_SERVER["HTTP_REFERER"]) : new Alert("Veuillez renseigner la date de debut de votre location !!", "../../Views/user/index.php");
                                        $alert->show_message();
                                    }
                                } else {
                                    $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Mode de paiement invalide !!", $_SERVER["HTTP_REFERER"]) : new Alert("Mode de paiement invalide !!", "../../Views/user/index.php");
                                    $alert->show_message();
                                }
                            } else {
                                $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Type de logement invalide !!", $_SERVER["HTTP_REFERER"]) : new Alert("Type de logement invalide !!", "../../Views/user/index.php");
                                $alert->show_message();
                            }
                        } else {
                            $alert = $_SERVER["HTTP_REFERER"] ? new Alert("La localisation du logement invalide !!", $_SERVER["HTTP_REFERER"]) : new Alert("La localisation du logement invalide !!", "../../Views/user/index.php");
                            $alert->show_message();
                        }
                    } else {
                        $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Le prix du logement invalide !!", $_SERVER["HTTP_REFERER"]) : new Alert("Le prix du logement invalide !!", "../../Views/user/index.php");
                        $alert->show_message();
                    }
                } else {
                    $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Vous ne pouvez pas louer votre propre logement !!", $_SERVER["HTTP_REFERER"]) : new Alert("Vous ne pouvez pas louer votre propre logement !!", "../../Views/user/index.php");
                    $alert->show_message();
                }

            } else {
                $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Le nom du propriétaire du logement invalide !!", $_SERVER["HTTP_REFERER"]) : new Alert("Le nom du propriétaire du logement invalide !!", "../../Views/user/index.php");
                $alert->show_message();
            }
        } else {
            $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Le nom censé vous appartenire ne correpsond à aucun nom dans nos données !!", $_SERVER["HTTP_REFERER"]) : new Alert("Le nom censé vous appartenire ne correpsond à aucun nom dans nos données !!", "../../Views/user/index.php");
            $alert->show_message();
        }
    }
} else if ($_SERVER["HTTP_REFERER"]) {
    header("location:" . $_SERVER["HTTP_REFERER"]);
} else {
    header("location: ../../Views/user/index.php");
}

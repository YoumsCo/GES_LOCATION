<?php

use Controllers\Proprio\Controller;
use Models\Admin\BienImmobilier;
use Models\Admin\Localisation;
use Models\Admin\Mode;
use Models\Admin\Type;
use Models\Admin\User;
use Models\Proprio\Image;


require_once "../../vendor/autoload.php";
require_once "../../Tools/tools.php";
require_once "../../Controllers/User/Controller.php";

$mode = new Mode();
$users = new User();
$biens = new BienImmobilier();
$localisation = new Localisation();
$types = new Type();
$filesDatas = new Controller();
$imagesDatas = new Image();

$allmodes = $mode->get_modes();
$allLocalisation = $localisation->get_localisation();
$allTypes = $types->get_types();
$all_users = $users->getUsers();

if (isset($_COOKIE["login"])) {
    $user_datas = $users->get_one("email", $_COOKIE["login"]);
    foreach ($user_datas as $one) {
        $nom = $one["nom"];
        $id = $one["iduser"];
        $email = $one["email"];
    }
} else {
    $nom = "";
}

if (!isset($_GET["token"]) || !$_GET["token"]) {
    if ($_SERVER["HTTP_REFERER"]) {
        header("location: " . $_SERVER["HTTP_REFERER"]);
    } else {
        header("location: ./index.php");
    }
} else {
    $allBiens = $biens->get_one("id_bien", base64_decode($_GET["token"]));
}

$rep = "../../houses/";
$picture_rep = "../../profiles/";

$files = $filesDatas->images($rep);

?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Youms C">
    <meta name="description" content="Site de prestation de logements">
    <link rel="stylesheet" href="../../Styles/show_one.css">
    <link rel="stylesheet" href="../../Styles/tools.css">
    <link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
    <title>GES-LOCATION | Un appart</title>
</head>

<body>
    <?php
    include_once "../loaders/loader_2.php";
    include_once '../layouts/side.php';

    ?>

    <div id="container">
        <div id="space">
            <button type="button" class="space-buttons"><a href="../user/connexion.php">Connexion</a></button>
            <button type="button" class="space-buttons"><a href="../user/connexion.php">Nous rejoindre</a></button>
            <?php
            if (!empty($pictures)) {
                foreach ($pictures as $file):
                    ?>
                    <img src="<?= (string) $picture_rep . $file ?>" alt="<?= $name ?>" id="space-profile">
                    <?php
                endforeach;
            } else {
                ?>
                <img src="../../Img/use.png" alt="<?= $name ?>" id="space-profile">
                <?php
            }
            ?>
            <span id="space-name"><?= $name ?></span>
        </div>

        <div id="contain-one">
            <?php
            foreach ($allBiens as $bien):
                ?>
                <div id="contain-img">
                    <?php
                    foreach ($files as $file):
                        [$file_email, $file_time, $file_other] = explode("_", $file);
                        [$file_id, $file_ext] = explode(".", $file_other);
                        if ($file_id == $bien["id_bien"]):
                            ?>
                            <img src="<?= (string) $rep . $file ?>" alt="Photo de profile" class="img">
                            <?php
                        endif;
                    endforeach;
                    ?>
                </div>
                <div id="contain-information">
                    <div id="contain-text">
                        <p>
                            <?= $bien["description"] ?>
                        </p>
                        <span id="contain-montant"><i class="fa fa-money"></i> &nbsp; Montant &nbsp;: &nbsp;
                            <strong><?= $bien["prix"] ?></strong> &nbsp; FCFA</span>
                        <?php
                        foreach ($allLocalisation as $location):
                            if ($location["id_localisation"] === $bien["id_localisation"]):
                                ?>
                                <span id="contain-location"><i class="fa fa-map-marker"></i> &nbsp; Localisation :
                                    &nbsp;<strong><?= $location["adresse"] ?></strong></span>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                    <div id="contain-button">
                        <?php
                        foreach ($all_users as $user):
                            if ($bien["iduser"] === $user["iduser"] && $user["telephone"] !== null):
                                ?>
                                <a href="tel:+<?= $user["telephone"] ?>" class="fa fa-mobile-phone contact"></a>
                                <?php
                            endif;
                        endforeach;
                        ?>
                        <button type="button" id="buy-button">Reserver</button>
                        <?php
                        foreach ($all_users as $user):
                            if ($bien["iduser"] === $user["iduser"] && $user["whatsapp"] !== null):
                                ?>
                                <a href="https://wa.me/+<?= $user["whatsapp"] ?>?text=Bonjour <?= $user["nom"] ?> je suis ici par rapport à la location d'un de vos logements sur GEST_LOCATION"
                                    class="fa fa-whatsapp contact" target="_blank" id="whatsapp"></a>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
                <div id="contain-scroll-buttons">
                    <i class="fa fa-chevron-circle-left contain-scroll"></i>
                    <i class="fa fa-chevron-circle-right contain-scroll"></i>
                </div>
                <?php
            endforeach;
            ?>
        </div>

        <div id="contain-all" class="contain-hide">
            <div id="contain-reserve-elements">
                <div id="contain-house-image">
                    <?php
                    foreach ($allBiens as $bien):
                        foreach ($files as $file):
                            [$file_email, $file_time, $file_other] = explode("_", $file);
                            [$file_id, $file_ext] = explode(".", $file_other);
                            if ($file_id == $bien["id_bien"]):
                                ?>
                                <img src="<?= (string) $rep . $file ?>" alt="img">
                                <?php
                                break;
                            endif;
                        endforeach;
                    endforeach;
                    ?>
                </div>
                <div id="contain-form">
                    <?php
                    foreach ($allBiens as $bien):
                        foreach ($all_users as $user):
                            foreach ($allLocalisation as $location):
                                foreach ($allTypes as $type):
                                    if ($user["iduser"] === $bien["iduser"] && $user["role"] === "proprietaire" && $location["id_localisation"] === $bien["id_localisation"] && $type['id_type'] === $bien["id_type"]):
                                        ?>
                                        <form action="<?= parse('../../Controllers/User/ReservationController.php'); ?>" method="POST"
                                            autocomplete="off" id="reservation-form">
                                            <div>
                                                <marquee behavior="scroll" direction="left">
                                                    <h2>Reservation</h2>
                                                </marquee>
                                            </div>
                                            <input type="hidden" name="matricule" value="<?= $bien["matricule"] ?>" class="none" required>
                                            <div class="none">
                                                <input type="text" name="name" id="name" placeholder="Votre nom" class="none"
                                                    value="<?= $nom ?>" required>
                                                <label for="name">Votre nom</label>
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <div class="none">
                                                <input type="text" name="name_p" id="name_p" placeholder="Nom du proprio" class="none"
                                                    value="<?= $user["nom"] ?>" required>
                                                <label for="name">Nom du propriétaire</label>
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <div class="none">
                                                <input type="text" name="price" id="price" placeholder="Prix du logement" class="none"
                                                    value="<?= $bien["prix"] ?>" required>
                                                <label for="price">Prix du logement</label>
                                                <i class="fa fa-bitcoin"></i>
                                            </div>
                                            <div class="none">
                                                <input type="text" name="location" id="location" placeholder="Localisation" class="none"
                                                    value="<?= $location["adresse"] ?>" required>
                                                <label for="location">Localisation</label>
                                                <i class="fa fa-map-marker"></i>
                                            </div>
                                            <div class="none">
                                                <input type="text" name="type" id="type" placeholder="Type de logement" class="none"
                                                    value="<?= $type["intitule"] ?>" required>
                                                <label for="type">Type</label>
                                                <i class="fa fa-home"></i>
                                            </div>
                                            <div>
                                                <input type="text" name="mode" list="list-mode" placeholder="Mode de paiement" id="mode"
                                                    required>
                                                <label for="mode">Mode de paiement</label>
                                                <i class="fa fa-id-card"></i>
                                                <datalist id="list-mode">
                                                    <?php foreach ($allmodes as $one): ?>
                                                        <option value="<?= $one["libelle"] ?>"></option>
                                                    <?php endforeach; ?>
                                                </datalist>
                                            </div>
                                            <div class="date">
                                                <input type="text" name="begin" id="begin" class="date-field" placeholder="Date de debut"
                                                    required>
                                                <label for="begin">Date de début</label>
                                                <i class="fa fa-calendar-check-o"></i>
                                            </div>
                                            <div class="date">
                                                <input type="text" name="deadline" id="deadline" class="date-field"
                                                    placeholder="Date de fin">
                                                <label for="deadline">Date de fin (Facultatif)</label>
                                                <i class="fa fa-calendar-times-o"></i>
                                            </div>
                                            <div>
                                                <button type="button" id="reset">Annuler</button>
                                                <button type="submit" name="reserve" id="reserve">Reserver</button>
                                            </div>
                                        </form>
                                        <?php
                                    endif;
                                endforeach;
                            endforeach;
                        endforeach;
                    endforeach;
                    ?>
                </div>
            </div>

        </div>
    </div>
    <?php
    include_once '../layouts/footer.php';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script type="module" src="../../js/show_one.js"></script>
</body>

</html>
<?php

use Controllers\Proprio\Controller;
use Models\Admin\BienImmobilier;
use Models\Admin\Localisation;
use
Models\Admin\User;
use Models\Proprio\Image;
require_once "../../vendor/autoload.php";
require_once "../../Tools/tools.php";
if (!isset($_COOKIE["role"]) || !isset($_COOKIE["login"])) {
    header("location:./auth.php");
} else if (
    isset($_COOKIE["role"]) && base64_decode($_COOKIE["role"])
    !== "proprietaire"
) {
    setcookie("role", "null", [
        "expires" => time() - 10,
        "path" => "/",
    ]);
    sleep(2);
    header("location:./auth.php");
}

$biens = new BienImmobilier();
$localisation = new Localisation();
$user = new User();
$controller = new Controller();
$images = new Image();

$allLocalisation = $localisation->get_localisation();
$imagesDatas = $images->get_images();
$user = $user->get_one("email", $_COOKIE["login"]);

if (!empty($user)) {
    foreach ($user as $value) {
        $id_user = $value["iduser"];
        $role = $value["role"];
    }
    if ($role !== "proprietaire") {
        setcookie("role", "null", [
            "expires" => time() - 10,
            "path" => "/",
        ]);
        sleep(2);
        header("location:./auth.php");
    }
} else {
    header("location:../user/index.php");
}
$houses = $biens->get_one("iduser", $id_user);

$rep = "../../houses/";
$files = $controller->get_images($rep, $_COOKIE["login"]);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Youms C">
    <meta name="description" content="Site de prestation de logements">
    <link rel="stylesheet" href="../../Styles/proprio/index.css">
    <link rel="stylesheet" href="../../Styles/tools.css">
    <link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">
    <title>GES-LOCATION | Espace propriétaire</title>
</head>

<body>
    <?php
    include_once "../loaders/loader_2.php";
    include_once "../layouts/side.php";
    ?>

    <div id="container">
        <div id="space">
            <!-- <input type="text" id="search" placeholder="Rechercher..." autocomplete="off">
            <i class="fa fa-search search-icon"></i> -->
            <div id="name-picture">
                <?php
                if (!empty($pictures)) {
                    foreach ($pictures as $file):
                        ?>
                        <img src="<?= (string) $picture_rep . $file ?>" alt="<?= $name ?>">
                        <?php
                    endforeach;
                } else {
                    ?>
                    <img src="../../Img/use.png" alt="<?= $name ?>">
                    <?php
                }
                ?>
                <span><?= $name ?></span>
            </div>
        </div>

        <div id="button">
            <a href="./add.php" id="add-button">Ajouter un logement</a>
        </div>

        <div id="content">
            <span id="counter">Total : <?= count($houses) ?></span>
            <?php
            if (is_array($houses) && !empty($houses)) {
                foreach ($houses as $value):
                    ?>
                    <div class="logement">
                        <div class="images">
                            <?php
                            $i = 0;
                            if (!empty($files)):
                                foreach ($files as $file):
                                    [$file_email, $file_time, $file_other] = explode("_", $file);
                                    [$file_id, $file_ext] = explode(".", $file_other);
                                    foreach ($imagesDatas as $data):
                                        if ($file_email === $_COOKIE["login"] && $file_id == $value["id_bien"]):
                                            $i++;
                                            $j = true;
                                            ?>
                                            <img src="<?= (string) $rep . $file ?>" alt="<?= $file_id ?>">
                                            <?php
                                        endif;
                                        break;
                                    endforeach;
                                endforeach;
                            endif;
                            ?>
                            <?php if ($i == 0 && !isset($j)): ?>
                                <span class="empty">Aucune image enrigstrée</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($i > 1): ?>
                            <div class="scrollButtons">
                                <i class="fa fa-chevron-circle-left"></i>
                                <i class="fa fa-chevron-circle-right"></i>
                            </div>
                        <?php endif; ?>
                        <div class="image-loader">
                            <div class="loader-rounded"></div>
                        </div>
                        <div class="desc-icons">
                            <p class="desc-content">
                                <?php
                                if ($id_user === $value["iduser"]):
                                    ?>
                                    <?= $value["description"] ?>
                                <?php endif; ?>
                            </p>
                            <?php
                            foreach ($allLocalisation as $location):
                                if ($location["id_localisation"] === $value["id_localisation"]):
                                    ?>
                                    <span class="location">
                                        <i class="fa fa-map-marker"></i>
                                        &nbsp;
                                        Localisation :
                                        <strong><?= $location["adresse"] ?></strong>
                                    </span>
                                    <?php
                                endif;
                            endforeach;
                            ?>

                            <span class="montant">
                                <i class="fa fa-money"></i>
                                &nbsp;
                                Montant :
                                <strong><?= $value["prix"] ?></strong> FCFA
                            </span>
                            <div class="icons">
                                <i class="fa fa-eye-slash eye"></i>
                                <a href="./upadate.php?token=<?= base64_encode($value["id_bien"]) ?>" class="fa fa-edit"></a>
                                <a href="../../Controllers/Proprio/DeleteController.php?token=<?= base64_encode($value["id_bien"]) ?>"
                                    class="fa fa-trash-o"></a>
                            </div>
                        </div>
                    </div>
                    <?php
                endforeach;
            } else {
                ?>
                <strong>❌ Vous n'avez enregistré aucun logement ❌</strong>
                <?php
            }
            ?>
        </div>

        <i class="fa fa-chevron-circle-up scroll-icon-hide"></i>
    </div>

    <?php
    include_once "../layouts/footer.php";
    ?>

    <script type="module" src="../../js/proprio/index.js"></script>
</body>


</html>
<?php

use Models\Admin\BienImmobilier;
use Models\Admin\Recevoir;
use Models\Admin\User;
use Models\User\Location;

require_once "../../vendor/autoload.php";
require_once "../../Tools/tools.php";
require_once "../../Controllers/User/Controller.php";

$user = new User();
$recevoir = new Recevoir();
$locations = new Location();

if (!isset($_COOKIE["login"]) || empty($user->get_one("email", parse($_COOKIE['login'])))) {
    header("location:./index.php");
}

foreach ($user->get_one("email", parse($_COOKIE['login'])) as $data) {
    $id = $data["iduser"];
    $email = $data["email"];

    if ($data["role"] === "proprietaire") {
        $houses = new BienImmobilier();
        $number = count($houses->get_one("iduser", $id));
        $houses_count = "<span id='house-count'>";
        $houses_count .= "Nombre de Logement(s) : &nbsp;<strong>" . (string) $number . "</strong>";
        $houses_count .= "</span>";
    }
}

$location_count = count($locations->get_one("iduser", $id));
$houses_count ??= null;

$rep = "../../profiles/";
$files = get_profiles($rep, $email, $id);

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Youms C">
    <meta name="description" content="Site de prestation de logements">
    <link rel="stylesheet" href="../../Styles/profile.css">
    <link rel="stylesheet" href="../../Styles/tools.css">
    <link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="../../Icons/second.ico">
    <title>GES-LOCATION | Profile</title>
</head>

<body>
    <?php
    include_once "../loaders/loader_2.php";
    include_once '../layouts/side.php';
    ?>

    <div id="container">
        <div id="space">
            <?php
            if (!empty($files)) {
                foreach ($files as $file):
                    ?>
                    <img src="<?= (string) $rep . $file ?>" alt="<?= $name ?>" id="space-profile">
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
        <div id="content">
            <div id="profile">
                <div id="picture">
                    <?php
                    if (!empty($files)) {
                        foreach ($files as $file):
                            ?>
                            <img src="<?= (string) $rep . $file ?>" alt="<?= $name ?>">
                            <?php
                        endforeach;
                    } else {
                        ?>
                        <img src="../../Img/use.png" alt="<?= $name ?>">
                        <?php
                    }
                    ?>
                </div>
                <div id="informations">
                    <span id="name">Nom : &nbsp;<strong><?= $name ?></strong></span>
                    <span id="email">Email : &nbsp;<strong><?= $email ?></strong></span>
                    <span id="location-count">Nombre de location(s) :
                        &nbsp;<strong><?= $location_count ?></strong></span>
                    <?= $houses_count ?>

                    <div id="buttons">
                        <form action="../../Controllers/User/ProfileController.php" method="POST"
                            enctype="multipart/form-data">
                            <input type="file" name="file" id="file" accept=".jpg, .jpeg, .png, .gif">
                            <input type="submit" name="send">
                        </form>
                        <label for="file" class="fa fa-camera"></label>
                        <a href="#" class="fa fa-edit"></a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php
    include_once '../layouts/footer.php';
    ?>
    <script type="module" src="../../js/profile.js"></script>
</body>

</html>
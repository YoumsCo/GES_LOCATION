<?php

use Models\Admin\Recevoir;
use Models\Admin\User;

require_once "../../vendor/autoload.php";
require_once "../../Tools/tools.php";

$user = new User();
$recevoir = new Recevoir();

if (!isset($_COOKIE["login"]) || empty($user->get_one("email", parse($_COOKIE['login'])))) {
    header("location:./index.php");
}

foreach ($user->get_one("email", parse($_COOKIE['login'])) as $data) {
    $id = $data["iduser"];
}

?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Youms C">
    <meta name="description" content="Site de prestation de logements">
    <link rel="stylesheet" href="../../Styles/notification.css">
    <link rel="stylesheet" href="../../Styles/tools.css">
    <link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="../../Icons/second.ico">
    <title>GES-LOCATION | Notifications</title>
</head>

<body>
    <?php
    include_once "../loaders/loader_2.php";
    include_once '../layouts/side.php';

    ?>

    <div id="container">
        <div id="space">
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
        <div id="content">
            <?php
            if (!empty($recevoir->procedure($id))) {
                foreach ($recevoir->procedure($id) as $data):
                    $tab = preg_split("/\?/", $data["contenu"]);
                    [$year, $month, $date, $hour, $min, $sec] = preg_split("/[-]|[:]|\s/", $data["date"]);
                    ?>
                    <div class="notif">
                        <div class="body">
                            <i class="fa fa-wechat"></i>
                            <?php
                            for ($i = 0; $i < count($tab); $i++):
                                if (!strpos($tab[$i], "?")):
                                    ?>
                                    <p><?= $tab[$i] ?></p>
                                    <?php
                                endif;
                            endfor;
                            ?>
                        </div>
                        <div class="date">
                            <i class="fa fa-calendar"></i>
                            <span><?= "Le {$date} - {$month} - {$year} à {$hour}h {$min}min {$sec}s" ?></span>
                        </div>
                    </div>
                    <?php
                endforeach;
            } else {
                ?>
                <strong id="null">Aucune notification pour le moment</strong>
                <?php
            }
            ?>
        </div>

    </div>
    <?php
    include_once '../layouts/footer.php';
    ?>
    <script type="module" src="../../js/notification.js"></script>
</body>

</html>
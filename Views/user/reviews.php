<?php

session_start();

require_once "../../vendor/autoload.php";
require_once "../../Controllers/User/Controller.php";
require_once "../../Tools/tools.php";

use Models\Admin\Laisser;
use Models\Admin\User;
use Models\User\Avis;


$reviews = new Avis();
$leaves = new Laisser();
$users = new User();

$gets = $reviews->get_reviews();
$all_users = $users->getUsers();
$joins = $leaves->read();

$all = get_all("../../profiles/");
$profilesComments = $leaves->procedure();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Youms C">
    <meta name="description" content="Site de prestation de logements">
    <link rel="stylesheet" href="../../Styles/reviews.css">
    <link rel="stylesheet" href="../../Styles/tools.css">
    <link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">
    <title>GES-LOCATION | Avis</title>
</head>

<body>
    <?php
    include_once "../loaders/loader_2.php";
    include_once '../layouts/side.php';
    ?>

    <div id="container">
        <div id="space">
            <button type="button" class="space-buttons"><a href="./connexion.php">Connexion</a></button>
            <button type="button" class="space-buttons"><a href="./connexion.php">Nous rejoindre</a></button>
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
            <div id="reviews">
                <div id="review-first">
                    <h2>Avis <i class="fa fa-star-half-o"></i></h2>
                    <hr>
                    <h2>Donnez votre avis</h2>
                    <div id="review-form">
                        <form action="<?= parse('../../Controllers/User/AvisController.php') ?>" method="post"
                            autocomplete="off">
                            <div id="form-fields">
                                <div>
                                    <label for="note">Note :</label>
                                    <input type="number" name="note" id="note" min="1" max="5" step="1">
                                    <input type="text" name="key" id="key">
                                </div>
                                <textarea name="review" id="review" placeholder="Votre avis" minlength="3"
                                    maxlength="128" required></textarea>
                            </div>

                            <button type="submit" name="reviewSubmit" id="review-submit">Envoyer</button>
                        </form>
                    </div>
                </div>
                <hr>

                <?php for ($i = 0; $i < sizeof($gets); $i++): ?>
                    <div class="review-content">
                        <div class="review-header">
                            <!-- <?php
                            // foreach ($all as $file):
                            //     [$file_email, $time, $other] = explode("_", $file);
                            //     [$file_id, $other] = explode(".", $other);
                            //     foreach ($profilesComments as $profile):

                            //         if ($file_id == $profile["iduser"] && $profile["id_avis"] == $gets[$i]["id_avis"]) {
                            //             ?>
                            //             <img src="<?= (string) '../../profiles/' . $file ?>" alt="img" class="review-img">
                            //             <?php
                            //             $_SESSION["find"] = true;
                            //             break;
                            //         }
                            //     endforeach;
                            //     if (isset($_SESSION["find"])) {
                            //         continue;
                            //     } else {
                            //         null;
                            //     }
                            // endforeach;

                            ?> -->
                            <img src="../../Img/use.png" alt="img" class="review-img">
                            <div class="review-name-time">
                                <?php
                                foreach ($all_users as $user):
                                    foreach ($joins as $join):
                                        if ($gets[$i]["id_avis"] === $join["id_avis"] && $join["iduser"] === $user["iduser"]) {
                                            ?>
                                            <span class="name"><?= $user["nom"]; ?></span>

                                            <?php
                                        }
                                        if ($gets[$i]['id_avis'] === $join["id_avis"] && $join["iduser"] === $user["iduser"]):
                                            [$year, $month, $day] = explode("-", $join["date"]);
                                            [$hour, $minutes, $seconds] = preg_split("/:/", $day);
                                            [$realDay, $realHour] = preg_split("/\s/", $hour);
                                            $str = (string) "Le " . $realDay . "/" . $month . "/" . $year . " à " . $realHour . ":" . $minutes . ":" . $seconds;

                                            ?>
                                            <span class="date"><?= $str; ?></span>
                                            <?php
                                        endif;
                                    endforeach;
                                endforeach;
                                ?>
                                <div id="review-stars">
                                    <?php for ($j = 0; $j < $gets[$i]["note"]; $j++): ?>
                                        <i class="fa fa-star-half-o"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <div class="review-body">
                            <p>
                                <?= $gets[$i]["commentaire"]; ?>
                            </p>
                        </div>
                    </div>
                <?php endfor; ?>

            </div>
        </div>

        <div id="scroll-top">
            <i class="fa fa-chevron-circle-up"></i>
        </div>

        <div id="scroll-last-position">
            <i class="fa fa-chevron-circle-down"></i>
        </div>

    </div>

    <?php
    include_once '../layouts/footer.php';
    ?>

    <script type="module" src="../../js/reviews.js" async></script>
</body>

</html>
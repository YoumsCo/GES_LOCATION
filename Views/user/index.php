<?php

use Controllers\Proprio\Controller;
use Models\Admin\BienImmobilier;
use Models\Admin\Laisser;
use Models\Admin\Type;
use Models\Admin\User;
use Models\User\Avis;

require_once "../../vendor/autoload.php";

$reviews = new Avis();
$leaves = new Laisser();
$users = new User();
$bienImmobilier = new BienImmobilier();
$types = new Type();
$filesDatas = new Controller();

$gets = $reviews->get_reviews();
$all_users = $users->getUsers();
$joins = $leaves->read();
$allBienImmobilier = $bienImmobilier->get_bien_immobilier();
$allType = $types->get_types();

$rep = "../../houses/";
$files = $filesDatas->images($rep);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Youms C">
    <meta name="description" content="Site de prestation de logements">
    <link rel="stylesheet" href="../../Styles/home.css">
    <link rel="stylesheet" href="../../Styles/tools.css">
    <link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="../../Icons/second.ico">
    <title>GES-LOCATION | Accueil</title>
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
            <img src="../../Img/use.png" alt="Photo de profile" id="space-profile">
        </div>
        <div id="header">
            <?php
            include_once '../layouts/searchBar.php';
            ?>
            <div id="picture">
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
            </div>
        </div>

        <div id="filter">
            <i class="fa fa-filter">Filtre : </i>
            <button type="button" class="filter-buttons active-choise">Tout</button>
            <?php foreach ($allType as $type): ?>
                <button type="button" class="filter-buttons"><?= $type["intitule"] ?></button>
            <?php endforeach; ?>
        </div>

        <hr>
        <div id="content">

            <?php
            $i = 0;
            foreach ($allBienImmobilier as $bien):
                if ($i % 2 === 0) {
                    foreach ($allType as $type):
                        if ($bien["id_type"] === $type["id_type"]):
                            ?>
                            <div class="habitat <?= $type["intitule"] ?>">
                                <div class="images">
                                    <?php
                                    foreach ($files as $file):
                                        [$file_email, $file_time, $file_other] = explode("_", $file);
                                        [$file_id, $file_ext] = explode(".", $file_other);
                                        if ($file_id == $bien["id_bien"]):
                                            ?>

                                            <img src="<?= (string) $rep . $file ?>" alt="Image" class="img">

                                            <?php
                                            break;
                                        endif;
                                    endforeach;
                                    ?>
                                    <div class="image-loader"></div>
                                </div>
                                <div class="description">
                                    <p class="text">
                                        <?= $bien["description"] ?>
                                    </p>
                                    <div class="button">
                                        <button>
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            &nbsp;
                                            <a href="./show_one.php?token=<?= base64_encode($bien["id_bien"]) ?>">Voir plus</a>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endif;
                    endforeach;
                } else {
                    foreach ($allType as $type):
                        if ($bien["id_type"] === $type["id_type"]):
                            ?>
                            <div class="habitat <?= $type["intitule"] ?>">
                                <div class="images">
                                    <?php
                                    foreach ($files as $file):
                                        [$file_email, $file_time, $file_other] = explode("_", $file);
                                        [$file_id, $file_ext] = explode(".", $file_other);
                                        if ($file_id == $bien["id_bien"]):
                                            ?>

                                            <img src="<?= (string) $rep . $file ?>" alt="Image" class="img">

                                            <?php
                                            break;
                                        endif;
                                    endforeach;
                                    ?>
                                    <div class="image-loader"></div>
                                </div>
                                <div class="description">
                                    <p class="text">
                                        <?= $bien["description"] ?>
                                    </p>
                                    <div class="button">
                                        <button>
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            &nbsp;
                                            <a href="./show_one.php?token=<?= base64_encode($bien["id_bien"]) ?>">Voir plus</a>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endif;
                    endforeach;
                    ?>
                    <div class="ads">
                        <span class="info">
                            Logoments bientot disponibles !!
                        </span>
                        <div class="ads-img">
                            <?php foreach ($files as $file): ?>
                                <img src="<?= (string) $rep . $file ?>" alt="image" class="ads-images">
                            <?php endforeach; ?>
                        </div>
                        <div class="ads-buttons">
                            <i class="fa fa-chevron-circle-left scroll-buttons-left"></i>
                            <i class="fa fa-chevron-circle-right scroll-buttons-right"></i>
                        </div>
                    </div>
                    <?php
                }
                $i >= floor(sizeof($allBienImmobilier) / 2) ? $i += 2 : $i++;
            endforeach;

            ?>

            <hr />
            <div id="reviews">
                <div id="review-first">
                    <h2>Avis <i class="fa fa-star-half-o"></i></h2>
                    <a href="./reviews.php"><i class="fa fa-eye"></i>&nbsp;Tout voir</a>
                </div>
                <hr>
                <h2>Donnez votre avis</h2>

                <?php for ($j = 0; $j <= 2; $j++): ?>

                    <div class="review-content">
                        <div class="review-header">
                            <img src="../../Img/use.png" alt="img" class="review-img">
                            <div class="review-name-time">

                                <?php
                                foreach ($all_users as $user):
                                    foreach ($joins as $join):
                                        if ($gets[$j]["id_avis"] === $join["id_avis"] && $join["iduser"] === $user["iduser"]) {
                                            ?>
                                            <span class="name"><?= $user["nom"]; ?></span>

                                            <?php
                                        }
                                        if ($gets[$j]['id_avis'] == $join["id_avis"] && $join["iduser"] === $user["iduser"]):
                                            [$year, $month, $day] = explode("-", $join["date"]);
                                            [$hour, $minutes, $seconds] = preg_split("/:/", $day);
                                            [$realDay, $realHour] = preg_split("/\s/", $hour);
                                            $str = "Le " . (string) $realDay . "/" . $month . "/" . $year . " à " . $realHour . ":" . $minutes . ":" . $seconds;

                                            ?>
                                            <span class="date"><?= $str; ?></span>
                                            <?php
                                        endif;
                                    endforeach;
                                endforeach;
                                ?>

                                <div id="review-stars">
                                    <?php for ($i = 0; $i < $gets[$j]["note"]; $i++): ?>
                                        <i class="fa fa-star-half-o"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <div class="review-body">
                            <p>
                                <?= $gets[$j]["commentaire"] ?>
                            </p>
                        </div>
                    </div>

                <?php endfor; ?>

            </div>
        </div>

        <div id="scroll-top-button">
            <i class="fa fa-chevron-circle-up"></i>
        </div>

        <div id="scroll-last-position-button">
            <i class="fa fa-chevron-circle-down"></i>
        </div>

    </div>

    <?php
    include_once '../layouts/footer.php';
    ?>

    <script type="module" src="../../js/home.js" async></script>
</body>


</html>
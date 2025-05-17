<?php

use Models\Admin\BienImmobilier;
use Models\Admin\Mode;
use Models\Admin\Type;
use Models\Admin\User;
use Models\User\Location;

require_once "../../vendor/autoload.php";

$location = new Location();
$types = new Type();
$users = new User();
$modes = new Mode();
$biens = new BienImmobilier();

if (!isset($_COOKIE["login"]) || empty($users->get_one("email", $_COOKIE["login"]))) {
    header("location:./index.php");
}

foreach ($users->get_one("email", $_COOKIE["login"]) as $user) {
    $iduser = $user["iduser"];
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Youms C">
    <meta name="description" content="Site de prestation de logements">
    <link rel="stylesheet" href="../../Styles/historique.css">
    <link rel="stylesheet" href="../../Styles/tools.css">
    <link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="../../Icons/second.ico">
    <title>GES-LOCATION | A propos</title>
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

        <table>
            <tr>
                <th>Logement</th>
                <th>Mode de paiement</th>
                <th>Date de debut</th>
                <th>Date de fin</th>
                <th>Montant</th>
                <th>Date</th>
            </tr>
            <?php
            if (count($location->get_one("iduser", $iduser)) > 0) {
                foreach ($location->get_one("iduser", $iduser) as $key):
                    if ($key["iduser"] === $iduser):
                        ?>
                        <tr>
                            <?php
                            foreach ($types->get_types() as $type):
                                foreach ($biens->get_bien_immobilier() as $bien):
                                    if ($bien["id_type"] === $type["id_type"] && $bien["id_bien"] === $key["id_bien"]):
                                        ?>
                                        <td><?= $type["intitule"] ?></td>
                                        <?php
                                    endif;
                                endforeach;
                            endforeach;
                            ?>
                            <?php
                            foreach ($modes->get_modes() as $mode):
                                if ($key["id_mode"] === $mode["id_mode"]):
                                    ?>
                                    <td><?= $mode["libelle"] ?></td>
                                    <?php
                                endif;
                            endforeach;
                            ?>
                            <td><?= $key["date_debut"] ?></td>
                            <td><?= $key["date_fin"] === "0000-00-00" ? "Vide" : $key["date_fin"] ?></td>
                            <td><?= $key["montant"] ?> FCFA</td>
                            <td><?= $key["date"] ?></td>
                        </tr>
                        <?php
                    endif;
                endforeach;
            } else {
                ?>
                <tr>
                    <td>Null</td>
                    <td>Null</td>
                    <td>Null</td>
                    <td>Null</td>
                    <td>Null</td>
                    <td>Null</td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>

    <?php
    include_once '../layouts/footer.php';
    ?>
    <script type="module" src="../../js/historique.js" async></script>
</body>

</html>
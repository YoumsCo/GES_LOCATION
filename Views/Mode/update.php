<?php

use Models\Admin\Mode;
use Models\Admin\User;

require_once "../../vendor/autoload.php";
require_once "../../Tools/tools.php";

$user = new User();
$mode = new Mode();

if (!isset($_GET["token"]) && !is_numeric($_GET["token"])) {
    header("location: ../user/connexion.php");
} else {
    foreach ($mode->get_one("id_mode", base64_decode($_GET["token"])) as $data) {
        $adresse = $data["libelle"];
    }
}

if (!isset($_COOKIE["login"])) {
    header("location: ../user/connexion.php");
} else if (isset($_COOKIE["login"]) && empty($user->get_one("email", $_COOKIE["login"]))) {
    header("location: ../user/connexion.php");
} else {
    foreach ($user->get_one("email", $_COOKIE["login"]) as $data) {
        if ($data["role"] !== "admin") {
            setcookie("login", "null", [
                "expires" => time() - 10,
                "path" => "/",
            ]);
            sleep(1);
            header("location: ../user/connexion.php");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Youms C">
    <meta name="description" content="Site de prestation de logements">
    <link rel="stylesheet" href="../../Styles/admin/Type/update.css">
    <link rel="stylesheet" href="../../Styles/tools.css">
    <link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">
    <title>GES-LOCATION | modifer <?= $adresse ?></title>
</head>

<body>
    <?php
    include_once "../layouts/side.php";
    include_once "../loaders/loader_2.php";
    ?>

    <div id="container">
        <div id="space"></div>
        <form action="<?= parse('../../Controllers/Mode/Controller.php') ?>" method="POST" autocomplete="off"
            id="auth-form">
            <div class="div">
                <h2>Modifier <strong><u><?= $adresse ?></u></strong></h2>
            </div>
            <div class="div">
                <input type="text" name="libelle" id="libelle" value="<?= $adresse ?>" class="div-input"
                    minlength="3" maxlength="30" placeholder="Intitulé du type" required>
                <i class="fa fa-money"></i>
                <label for="libelle" class="label">Intitulé</label>
                <input type="hidden" name="token" value="<?= $_GET["token"] ?>" required>
            </div>
            <div class="div">
                <button type="submit" class="form-button" name="send">Soumettre</button>
            </div>
        </form>
    </div>

    <?php
    include_once "../layouts/footer.php";
    ?>
</body>

</html>
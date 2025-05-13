<?php

use Models\Admin\User;

require_once "../../vendor/autoload.php";
require_once "../../Tools/tools.php";

if (isset($_COOKIE["role"]) || !isset($_COOKIE["login"])) {
    header("location: ../user/connexion.php");
}

$user = new User();
foreach ($user->get_one("email", $_COOKIE["login"]) as $value) {
    $nom = $value["nom"];
    $email = $value["email"];
    $role = $value["role"];

    if (isset($role) && $role == "proprietaire" && !isset($_COOKIE["role"]) && !isset($_COOKIE["logout"])) {
        setcookie("role", base64_encode("proprietaire"), [
            "expires" => time() + 10 * 365 * 24 * 60 * 60,
            "path" => "/",
        ]);
        sleep(3);
        header("location:./index.php");
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
    <link rel="stylesheet" href="../../Styles/proprio/auth.css">
    <link rel="stylesheet" href="../../Styles/tools.css">
    <link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.0/build/css/intlTelInput.css">
    <link rel="stylesheet" href="../../intl-tel-input-25.3.0/src/css/demo.scss">
    <title>GES-LOCATION | Authentification</title>
</head>

<body>
    <?php
    include_once "../layouts/side.php";
    include_once "../loaders/loader_2.php";
    ?>

    <div id="container">
        <form action="<?= parse('../../Controllers/Proprio/ProprietaireController.php') ?>" method="POST"
            autocomplete="off" id="auth-form">
            <div class="div">
                <marquee behavior="scroll" direction="left">
                    <h2>Devenez propriétaire ici</h2>
                </marquee>
            </div>
            <div class="div event-none">
                <label for="nom" class="label">Nom *</label>
                <input type="text" name="nom" id="nom" class="div-input" value="<?= $nom ?>" required>
                <i class="fa fa-user"></i>
            </div>
            <div class="div event-none">
                <label for="email" class="label">Email *</label>
                <input type="text" name="email" id="email" class="div-input" value="<?= $email ?>" required>
                <i class="fa  fa-envelope"></i>
            </div>
            <div class="div">
                <label for="tel" class="label">Téléphone *</label>
                <input type="text" name="tel" id="tel" class="div-input numbers" required>
                <i class="fa fa-phone"></i>
            </div>
            <div class="div">
                <label for="whatsapp" class="label">Whatsapp <strong>(Facultatif mais recommandé)</strong></label>
                <input type="text" name="whatsapp" id="whatsapp" class="div-input numbers">
                <i class="fa fa-whatsapp"></i>
            </div>
            <div class="div">
                <button type="button" class="form-button">Vider les champ</button>
                <button type="submit" class="form-button" name="submit">Soumettre</button>
            </div>
        </form>
    </div>

    <?php
    include_once "../layouts/footer.php";
    ?>

    <script type="module" src="../../js/proprio/auth.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.0/build/js/intlTelInput.min.js"></script>
</body>

</html>
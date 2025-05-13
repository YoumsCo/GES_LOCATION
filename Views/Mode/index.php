<?php

use Models\Admin\Mode;
use Models\Admin\User;

require_once "../../vendor/autoload.php";

$user = new User();
$locations = new Mode();

if (!isset($_COOKIE["login"])) {
    header("location: ../user/connexion.php");
}
else if (isset($_COOKIE["login"]) && empty($user->get_one("email", $_COOKIE["login"]))) {
    header("location: ../user/connexion.php");
}
else {
    foreach($user->get_one("email", $_COOKIE["login"]) as $data) {
        if($data["role"] !== "admin") {
            setcookie("login", "null", [
                "expires" => time() - 10,
                "path" => "/",
            ]);
            sleep(1);
            header("location: ../user/connexion.php");
        }
    }
}

$modesDatas = $locations->get_modes();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Youms C">
    <meta name="description" content="Site de prestation de logements">
    <link rel="stylesheet" href="../../Styles/admin/Type/index.css">
    <link rel="stylesheet" href="../../Styles/tools.css">
    <link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">
    <title>GES-LOCATION | Gestion des localisations</title>
</head>

<body>
    <?php
    include_once "../loaders/loader_2.php";
    include_once '../layouts/side.php';

    ?>

    <div id="container">
        <div id="space"></div>
        <a href="./add.php" id="add-button">Ajouter un mode</a>
        <table>
            <tr>
                <th>Libelle</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
            <?php foreach ($modesDatas as $data): ?>
                <tr>
                    <td><?= $data["libelle"] ?></td>
                    <td><a href="<?= './update.php?token='. base64_encode($data["id_mode"])?>" class="fa fa-edit"></a></td>
                    <td><a href="<?= '../../Controllers/Mode/Controller.php?token='. base64_encode($data["id_mode"])?>" class="fa fa-trash-o"></a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php
    include_once '../layouts/footer.php';
    ?>

    <script type="module" src="../../js/admin/Type/index.js" async></script>
</body>


</html>
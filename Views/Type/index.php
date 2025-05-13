<?php

use Models\Admin\Type;
use Models\Admin\User;

require_once "../../vendor/autoload.php";

$user = new User();
$types = new Type();

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

$typeDatas = $types->get_types();

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
    <title>GES-LOCATION | Gestion des types</title>
</head>

<body>
    <?php
    include_once "../loaders/loader_2.php";
    include_once '../layouts/side.php';

    ?>

    <div id="container">
        <div id="space"></div>
        <a href="./add.php" id="add-button">Ajouter un type</a>
        <table>
            <tr>
                <th>Intitulé</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
            <?php foreach ($typeDatas as $type): ?>
                <tr>
                    <td><?= $type["intitule"] ?></td>
                    <td><a href="<?= './update.php?token='. base64_encode($type["id_type"])?>" class="fa fa-edit"></a></td>
                    <td><a href="<?= '../../Controllers/Type/Controller.php?token='. base64_encode($type["id_type"])?>" class="fa fa-trash-o"></a></td>
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
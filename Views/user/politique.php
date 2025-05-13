<?php


?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Youms C">
    <meta name="description" content="Site de prestation de logements">
    <link rel="stylesheet" href="../../Styles/politique.css">
    <link rel="stylesheet" href="../../Styles/tools.css">
    <link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">
    <title>GES-LOCATION | Politique</title>
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
        <div id="content">
            <p>Content</p>
        </div>

    </div>
    <?php
    include_once '../layouts/footer.php';
    ?>
    <script type="module" src="../../js/politique.js"></script>
</body>

</html>
<?php

use Controllers\Proprio\Controller;
use Models\Admin\Recevoir;
use Models\Admin\User;

require_once "../../vendor/autoload.php";
require_once "../../Tools/tools.php";
require_once "../../Controllers/User/Controller.php";

$user = new User();

if (isset($_COOKIE["login"]) && !empty($user->get_one("email", parse($_COOKIE['login'])))) {
    foreach ($user->get_one("email", parse($_COOKIE['login'])) as $user) {
        $name = $user['nom'];
        $id = $user["iduser"];
        $e_e = $user['email'];
        if ($user["role"] === "admin") {

            $links = '<li class="links">';
            $links .= '<i class="fa fa-list"></i>';
            $links .= '<a href="../Type/index.php" class="' . current_page("Type/index") . '">Type</a>';
            $links .= '</li>';

            $links .= '<li class="links">';
            $links .= '<i class="fa fa-map-marker"></i>';
            $links .= '<a href="../Localisation/index.php" class="' . current_page("Localisation/index") . '">Localisation</a>';
            $links .= '</li>';

            $links .= '<li class="links">';
            $links .= '<i class="fa fa-credit-card-alt"></i>';
            $links .= '<a href="../Mode/index.php" class="' . current_page("Mode/index") . '">Mode</a>';
            $links .= '</li>';

        } else {
            $proprioSpace = '<li class="links">';
            $proprioSpace .= '<i class="fa fa-users"></i>';
            $proprioSpace .= '<a href="../proprio/index.php" class="' . current_page("proprio/index") . '">Espace propriétaire</a>';
            $proprioSpace .= '</li>';

            $profile = '<li class="links">';
            $profile .= '<i class="fa fa-user-secret"></i>';
            $profile .= '<a href="./profile.php" class="' . current_page("profile") . '">Proflle</a>';
            $profile .= '</li>';

            $user = new User();

            if (isset($_COOKIE["login"]) && !empty($user->get_one("email", $_COOKIE["login"]))) {
                $historique = '<li class="links">';
                $historique .= '<i class="fa fa-list-alt"></i>';
                $historique .= '<a href="../user/historique.php" class="' . current_page("historique") . '">Historique</a>';
                $historique .= '</li>';

                $recevoir = new Recevoir();
                $count = count($recevoir->procedure($id));

                $notification = '<a href="../user/notification.php" class="fa fa-bell-o" count="' . (string) $count . '" id="notification-icon"></a>';
            }
        }
    }

} else {
    $name = "User" . " " . uniqid();
    $e_e = "null";
    $id = null;

    setcookie("login", "null", [
        "expires" => time() - 10,
        "path" => "/",
    ]);
    sleep(1);
}

$admin ??= null;
$links ??= null;
$proprioSpace ??= null;
$profile ??= null;
$historique ??= null;
$notification ??= null;

$picture_rep = "../../profiles/";
$pictures = get_profiles($picture_rep, $e_e, $id);
?>


<link rel="stylesheet" href="../../Styles/layouts/side.css">
<link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">

<div id="side-container">
    <div id="side-header">
        <?php
        if (!empty($pictures)) {
            foreach ($pictures as $file):
                ?>
                <img src="<?= (string) $picture_rep . $file ?>" alt="<?= $name ?>" id="side-header-picture">
                <?php
            endforeach;
        } else {
            ?>
            <img src="../../Img/use.png" alt="<?= $name ?>" id="side-header-picture">
            <?php
        }
        ?>
        <div id="side-header-name-icons">
            <div id="side-header-name">
                <span id="name"><?= $name; ?></span>
                <input type="hidden" id="e_value" value="<?= $e_e; ?>">
            </div>
            <div id="side-header-icons">
                <?= $admin ?>
                <?= $notification ?>

                <?php if (isset($_COOKIE["login"])): ?>
                    <i class="fa fa-sign-out" id="sign-out-icon"></i>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <ul id="side-body">
        <li class="links">
            <i class="fa fa-home"></i>
            <a href="../user/index.php" class="<?= current_page("user/index") ?>">Accueil</a>
        </li>

        <?= $links ?>

        <?= $proprioSpace ?>

        <?= $profile ?>

        <li class="links"><i class="fa fa-lock"></i><a href="../user/politique.php"
                class="<?= current_page("politique") ?>">Politique</a>
        </li>
        <?= $historique ?>

        <li class="links">
            <i class="fa fa-mobile"></i>
            <span>Contact</span>
            <ul class="links-child">
                <a href="https://wa.me/+237690552385?text=Bonjour je suis un utilisateur de la plateforme GEST_LOCATION et je voudrai discuter avec vous s'il vous plait."
                    target="_blank"><i class="fa fa-whatsapp" aria-hidden="true"></i>Whatsapp</a>
                <a href="mailto:youmschoco@gmail.com"><i class="fa fa-envelope" aria-hidden="true"></i>Email</a>
                <a href="tel:+237690552385"><i class="fa fa-volume-control-phone" aria-hidden="true"></i>Téléphone</a>
            </ul>
        </li>
        <li class="links">
            <i class="fa fa-info-circle"></i>
            <a href="../user/info.php" class="<?= current_page("info") ?>">A propos</a>
        </li>
    </ul>
    <footer id="side-footer">
        &copy;
        <h3>GES-LOCATION 2025</h3>
    </footer>
</div>
<div id="out"></div>

<div id="menu">
    <div></div>
    <div></div>
    <div></div>
</div>
<script src="../../js/layouts/side.js" type="module" defer></script>
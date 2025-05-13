<?php

use Models\Admin\User;

require_once "../../vendor/autoload.php";
require_once "../../Tools/tools.php";
$user = new User();

if (isset($_COOKIE["login"]) && !empty($user->get_one("email", parse($_COOKIE['login'])))) {
    foreach ($user->get_one("email", parse($_COOKIE['login'])) as $user) {
        if ($user["role"] !== "admin") {
            $profile = '<li class="links"><a href="#" class="' . current_page("profile") . '">Profile</a></li>';
        }
    }

}

$profile ??= null;

?>


<link rel="stylesheet" href="../../Styles/layouts/footer.css">

<footer id="footer-container">
    <h3>
        <span>GES-LOCATION</span>
        <p>Site de prestation de logements</p>
    </h3>
    <div id="footer-links">
        <div id="logo">
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
        </div>
        <ul>
            <li class="links"><a href="../user/index.php" class="<?= current_page("user/index") ?>">Accueil</a></li>
            <li class="links"><a href="../user/info.php" class="<?= current_page("user/info") ?>">A propos</a></li>
            <li class="links"><a href="https://wa.me/+237690552385?text=Bonjour je viens de GEST_LOCATION"
                    target="_blank">Contact</a></li>
        </ul>
        <ul>
            <?= $profile ?>
            <li class="links"><a href="../user/reviews.php" class="<?= current_page("reviews") ?>">Avis</a></li>
            <li class="links"><a href="../user/politique.php" class="<?= current_page("user/politique") ?>">Politique de
                    confidentialité</a></li>
        </ul>
    </div>
    <div id="footer-bottom">
        <p>&copy; <?= date("Y") ?> GES-LOCATION | Tous droits réservés</p>
    </div>
</footer>

<script type="module" src="../../js/layouts/footer.js" async></script>
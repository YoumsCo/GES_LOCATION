<?php

use Models\Admin\User;
use Models\User\Avis;
use Models\User\User as UserUser;

require_once '../../vendor/autoload.php';
require_once '../../Tools/tools.php';
require_once '../../Tools/Alert.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['HTTP_REFERER'], 'Views/user/reviews.php')) {
    if (preg_match("/^\d{1}$/", $_POST['note'])) {
        $note = parse($_POST['note']);

        if (preg_match('/^[a-zA-Z]{2,}/', $_POST['review'])) {
            $comment = parse(ucfirst($_POST['review']));

            $user = new UserUser();

            if ($_POST['key'] && $user->checkUser($_POST['key'])) {
                $review = new Avis();
                $user = new User();

                $userValues = $user->get_one("email", parse($_POST['key']));
                foreach ($userValues as $value) {
                    $id = $value['iduser'];
                }
                if (is_numeric($id)) {
                    $review->createReview($id, $note, $comment);

                    $alert = new Alert("Avis inséré. Allez voir de vous-meme. :)", "../../Views/user/reviews.php");
                    $alert->show_message();

                } else {
                    header('location: ../../Views/user/reviews.php');
                }

            } else {
                header('location: ../../Views/user/reviews.php');
            }
        } else {
            $alert = $_SERVER["HTTP_REFERER"] ? new Alert("Vueillez entrez un texte correcte !!", $_SERVER["HTTP_REFERER"]) : new Alert("Vueillez entrez un texte correcte !!", "../../Views/user/index.php");
            $alert->show_message();
        }
    } else {
        $alert = $_SERVER["HTTP_REFERER"] ? new Alert("La note doit etre supérieure ou égale à 1 et inférieure ou égale à 5 !!", $_SERVER["HTTP_REFERER"]) : new Alert("La note doit etre supérieure ou égale à 1 et inférieure ou égale à 5 !!", "../../Views/user/index.php");
        $alert->show_message();
    }
} else if ($_SERVER['HTTP_REFERER']) {
    header('location:' . $_SERVER['HTTP_REFERER']);
} else {
    header('location: ../../Views/user/index.php');
}

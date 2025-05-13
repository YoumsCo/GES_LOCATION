<?php

use Models\Admin\BienImmobilier;
use Models\Admin\Localisation;
use Models\Admin\Type;
use Models\Proprio\Image;

require_once "../../vendor/autoload.php";
require_once "../../Tools/tools.php";

$locations = new Localisation();
$types = new Type();
$biens = new BienImmobilier();
$images = new Image();


if (!isset($_COOKIE["role"]) || !isset($_COOKIE["login"])) {
    header("location: ../user/connexion.php");
}

if (
    !isset($_GET["token"])
    ||
    !strpos(strtolower($_SERVER["HTTP_REFERER"]), strtolower("Views/proprio/index"))
    ||
    !is_numeric(base64_decode(parse($_GET["token"])))
    ||
    empty($biens->get_one("id_bien", parse(base64_decode($_GET["token"]))))
    &&
    !empty($images->get_one("id_bien", parse(base64_decode($_GET["token"]))))
) {
    header("location: ./index.php");
}

if (
    !empty($biens->get_one("id_bien", parse(base64_decode($_GET["token"]))))
    &&
    !empty($images->get_one("id_bien", parse(base64_decode($_GET["token"]))))
) {
    foreach ($biens->get_one("id_bien", parse(base64_decode($_GET["token"]))) as $bien) {
        $id_type = $bien["id_localisation"]; 
        $position = $bien["id_localisation"]; 
        $nom = $bien["intitule"];
        $desc = $bien["description"];
        $prix = $bien["prix"];
    }

    foreach ($locations->get_one("id_localisation", $position) as $element) {
        $adresse = $element["adresse"];
    }

    foreach ($types->get_one("id_type", $id_type) as $element) {
        $intitule = $element["intitule"];
    }

    foreach ($images->get_one("id_bien", parse(base64_decode($_GET["token"]))) as $image) {
        $foreign_bien = $image["id_bien"];
        $taille = $image["taille"];
        $type = $image["type"];
    }
} else {
    echo "Man";
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Youms C">
    <meta name="description" content="Site de prestation de logements">
    <link rel="stylesheet" href="../../Styles/proprio/update.css">
    <link rel="stylesheet" href="../../Styles/tools.css">
    <link rel="stylesheet" href="../../font-awesome-4.7.0/css/font-awesome.min.css">
    <title>GES-LOCATION | Modifier un logement</title>
</head>

<body>
    <?php
    include_once "../layouts/side.php";
    include_once "../loaders/loader_2.php";
    ?>

    <div id="container">
        <form action='<?= parse("../../Controllers/Proprio/UpdateController.php") ?>' method="POST" autocomplete="off"
            id="auth-form" enctype="multipart/form-data">
            <div class="div">
                <marquee behavior="scroll" direction="left">
                    <h2>Modifiez le logement ici</h2>
                </marquee>
            </div>
            <div class="div">
                <input type="text" name="nom" id="nom" class="div-input" minlength="3" maxlength="30"
                    placeholder="Intitulé du logement" value="<?= $nom ?>">
                <i class="fa fa-home"></i>
                <label for="nom" class="label">Intitulé du logement (Facultatif)</label>
            </div>
            <div class="div">
                <label for="info">Description *</label>
                <textarea name="info" id="info" placeholder="Décrivez votre logement" cols="50" rows="3"
                    required><?= $desc ?></textarea>
                <i class="fa fa-info-circle"></i>
            </div>
            <div class="div">
                <input type="text" name="prix" id="prix" class="div-input input-require" placeholder="Prix du logement"
                    pattern="^\d{5,6}$" minlength="5" maxlength="6" value="<?= $prix ?>" required>
                <i class="fa fa-bitcoin"></i>
                <label for="prix" class="label">Prix *</label>
            </div>
            <div class="div">
                <input type="text" list="locationList" name="location" id="location" class="div-input input-require"
                    placeholder="Localisation du logement" pattern="^[a-zA-Z]+\s?[a-zA-Z0-9]*(-|'|_)?\s?[a-zA-Z0-9]*$"
                    value="<?= $adresse ?>" required>
                <i class="fa fa-map-marker"></i>
                <label for="location" class="label">Localisation</label>
                <datalist id="locationList">
                    <?php foreach ($locations->get_localisation() as $location): ?>
                        <option value="<?= $location["adresse"] ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>
            <div class="div">
                <input type="text" list="typesList" name="type" id="type" class="div-input input-require"
                    placeholder="Type de logement" pattern="^[a-zA-Z]{4,20}$" value="<?= $intitule ?>" required>
                <i class="fa fa-file-text-o"></i>
                <label for="type" class="label">Type</label>
                <datalist id="typesList">
                    <?php foreach ($types->get_types() as $type): ?>
                        <option value="<?= $type["intitule"] ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>
            <div class="div">
                <label for="files">Image(s) *</label>
                <input type="file" name="files[]" id="files" multiple class="div-input input-require"
                    accept=".jpg, .png, .jpeg" required>
                <i class="fa fa-image"></i>
            </div>
            <div class="div">
                <input type="hidden" name="token" id="token" class="div-input input-require" value="<?= parse($_GET["token"]) ?>"  required>
            </div>
            <div class="div">
                <button type="button" class="form-button">Vider les champ</button>
                <button type="submit" class="form-button" name="send">Soumettre</button>
            </div>
        </form>
    </div>

    <?php
    include_once "../layouts/footer.php";
    ?>

    <script type="module" src="../../js/proprio/update.js" async></script>
</body>

</html>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login and Registration Form</title>
    <link rel="stylesheet" href=".././../Styles/dark/connexion.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>
    <div class="wrapper">
        <span class="bg-animate"></span>
        <span class="bg-animate2"></span>
        <div class="form-box login active-form">
            <h4 class="animation" style="--i: o"></h4>
            <form action="./index.php" autocomplete="off">
                <div class="input-box animation">
                    <input type="text" required />
                    <label>EMAIL</label>
                    <i class="bx bxs-envelope"></i>
                </div>
                <div class="input-box animation" style="--i: 2">
                    <input type="password" required />
                    <label>MOT DE PASSE</label>
                    <i class="bx bxs-lock-alt cadenas"></i>
                </div>
                <button type="submit" class="btn animation" style="--1: 3" id="submit">
                    CONNEXION
                </button>
                <div class="logreg-link animation" style="--1: 4">
                    <p>
                        Don't have an account?
                        <a href="#" class="register-link" id="register-btn">S'INSCRIRE</a>
                    </p>
                </div>
            </form>
        </div>
        <div class="info-text login">
            <h4 class="animation" style="--i: 0">BIENVENUE DANS
                GES_LOCATION!</h4>
            <p class="animation" style="--i: 1">
                Decouvrez votre prochain chez-vous ideal en un clin d'oeil.
            </p>
        </div>

        <div class="form-box register">
            <h4 class="animation" style="--i: 17; --j: 0">S'INSCRIRE</h4>
            <form action="#" autocomplete="off">
                <div class="input-box animation" style="--i: 18; --j: 1">
                    <input type="text" required />
                    <label>NOM</label>
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box animation" style="--i: 19; --j: 2">
                    <input type="text" required />
                    <label>EMAIL</label>
                    <i class="bx bxs-envelope"></i>
                </div>
                <div class="input-box animation" style="--i: 20; --j: 3">
                    <input type="password" required />
                    <label>MOT DE PASSE</label>
                    <i class="bx bxs-lock-alt cadenas"></i>
                </div>
                <button type="submit" class="btn animation" style="--i: 21; --j: 4">
                    S'INSCRIRE
                </button>
                <div class="logreg-link animation" style="--i: 22; --j: 5">
                    <p>
                        Already have an account?
                        <a href="#" class="login-link" id="login-btn">CONNEXION</a>
                    </p>
                </div>
            </form>
        </div>
        <div class="info-text register">
            <h4 class="animation" style="--i: 17">BIENVENUE DANS
                GES_LOCATION!</h4>
            <p class="animation" style="--i: 18">
                Decouvrez votre prochain chez-vous ideal en un clin d'oeil.
            </p>
        </div>
    </div>
    <script src="../../js/connexion.js"></script>
</body>

</html>
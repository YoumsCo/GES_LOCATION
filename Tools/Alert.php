<?php
class Alert
{
    private string $message;
    private string|null $link;

    function __construct(string $message, string|null $link = null)
    {
        $this->message = $message;
        $this->link = $link;
    }

    function show_message(): void
    {
        echo "<html lang='fr'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<link rel='stylesheet' href='../../Styles/controllers.css'>";
        echo "<link rel='stylesheet' href='../../Styles/tools.css'>";
        echo "<link rel='stylesheet' href='../../font-awesome-4.7.0/css/font-awesome.min.css'>";
        echo "<title>GEST_LOCATION | proccess</title>";
        echo "</head>";
        echo "<body>";
        echo "<div id='container'></div>";
        echo "<script type='module'>";
        echo "import { createHTMLElement } from '../../js/tools.js';";
        echo "createHTMLElement('alert', '{$this->message}', null, null);";
        echo "setTimeout(function() {";
        echo "location.href = '{$this->link}';";
        echo "}, 4000);";
        echo "</script>";
        echo "</body>";
        echo "</html>";
    }

    function message_action(string $email): void
    {
        echo "<html lang='fr'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<link rel='stylesheet' href='../../Styles/controllers.css'>";
        echo "<link rel='stylesheet' href='../../Styles/tools.css'>";
        echo "<link rel='stylesheet' href='../../font-awesome-4.7.0/css/font-awesome.min.css'>";
        echo "<title>Document</title>";
        echo "</head>";
        echo "<body>";
        echo "<div id='container'></div>";
        echo "<script type='module'>";
        echo "import { createHTMLElement } from '../../js/tools.js';";
        echo "createHTMLElement('alert', '{$this->message}', null, null);";
        echo "localStorage.setItem('login', '" . (string) $email . "');";
        echo "setTimeout(function() {";
        echo "if(sessionStorage.getItem('referer')) {";
        echo "const ref = sessionStorage.getItem('referer');";
        echo "sessionStorage.removeItem('referer');";
        echo "document.location.href = ref;";
        echo "}";
        echo "else {";
        echo "document.location.href = '{$this->link}';";
        echo "}";
        echo "}, 2000);";
        echo "</script>";
        echo "</body>";
        echo "</html>";
    }

    function create_session(string $name, string $value = "set"): bool
    {
        try {
            echo "<html lang='fr'>";
            echo "<head>";
            echo "<meta charset='UTF-8'>";
            echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
            echo "<link rel='stylesheet' href='../../Styles/controllers.css'>";
            echo "<link rel='stylesheet' href='../../Styles/tools.css'>";
            echo "<link rel='stylesheet' href='../../font-awesome-4.7.0/css/font-awesome.min.css'>";
            echo "<title>Document</title>";
            echo "</head>";
            echo "<body>";
            echo "<div id='container'></div>";
            echo "<script type='text/javascript'>";
            echo "sessionStorage.setItem('{$name}', '{$value}');";
            echo "</script>";
            echo "</body>";
            echo "</html>";

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

header("../Views/user/index.php");
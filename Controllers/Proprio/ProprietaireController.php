<?php

if (file_get_contents("php://input")) {
    function checkData(string $option, string|int $data)
    {
        switch ($option) {
            case 'name':
                $regex = "/^[a-zA-Z]+\s?[a-zA-Z0-9]*(-|'|_)?\s?[a-zA-Z0-9]*$/";
                if (preg_match($regex, $data)) {
                    return true;
                } else {
                    return false;
                }
            case 'email':
                $regex = "/^[a-zA-Z]+\d{0,5}[.]{0,2}[a-zA-Z]*\d{0,5}[\@](gmail|yahoo|outlook)(\.)(com|fr)$/";
                if (preg_match_all($regex, $data)) {
                    return true;
                } else {
                    return false;
                }
            case 'tel':
                $regex = "/^\+?[0-9]{1,3}[0-9]{4,14}$/";
                if (preg_match_all($regex, $data)) {
                    return true;
                } else {
                    return false;
                }
            default:
                return false;
        }
    }

    function db(): PDOException|PDO
    {
        $database = $_ENV["DB_NAME"];
        $user = $_ENV["USER"];
        $password = $_ENV["PASSWORD"];
        $server = $_ENV["HOST"];
        $port = $_ENV["PORT"];
        $charset = $_ENV["CHARSET"];
        try {
            $dsn = "mysql:host={$server};dbname={$database};port={$port};charset={$charset}";
            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "set names utf8");

            return $pdo;
        } catch (PDOException $e) {
            return $e;
        }
    }

    function get_user(string $email): array|null
    {
        $pdo = db();
        $sql = "select * from user where email = ? and role = 'user'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->fetchAll();
    }

    function check_user(string $nom, string $email)
    {
        $user = get_user($_COOKIE["login"]);
        if ($user !== null) {
            foreach ($user as $data) {
                if ($data["nom"] === $nom && $data["email"] === $email && $data["role"] === "user") {
                    return true;
                } 
                else if ($data["nom"] === $nom && $data["email"] === $email && $data["role"] === "admin") {
                    return "admin";
                } 
                else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    function update_user(int $tel, int|null $whatsapp = null, string $email): bool
    {
        $sql = !empty($whatsapp) ? "update user set role = ?, telephone = ?, whatsapp = ? where email = ?" : "update user set role = ?, telephone = ?  where email = ? and role = 'user'";

        try {
            $pdo = db();
            $stmt = $pdo->prepare($sql);
            !empty($whatsapp) ? $stmt->execute(["proprietaire", $tel, $whatsapp, $email]) : $stmt->execute(["proprietaire", $tel, $email]);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    $datas = json_decode(file_get_contents("php://input"), true);
    $whatsapp = $datas["whatsapp"] ?? null;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        if (checkData("name", $datas["nom"])) {
            $nom = $datas["nom"];

            if (checkData("email", $datas["email"]) && isset($_COOKIE["login"]) && checkData("email", $_COOKIE["login"]) && check_user($nom, $_COOKIE["login"])) {
                $email = $datas["email"];

                if (checkData("tel", $datas["tel"])) {
                    $tel = $datas["tel"];
                    [$mark, $tel] = explode("+", $tel);

                    if (!empty($whatsapp) && checkData("tel", $whatsapp)) {
                        [$mark, $whatsapp] = explode("+", $whatsapp);
                        if (update_user($tel, $whatsapp, $email)) {
                            echo json_encode("success");
                            setcookie("role", base64_encode("proprietaire"), [
                                "expires" => time() + 10 * 365 * 24 * 60 * 60,
                                "path" => "/",
                            ]);
                        } else {
                            echo json_encode("failure");
                        }

                    } else {
                        if (update_user($tel, null, $email)) {
                            echo json_encode("success");
                            setcookie("role", base64_encode("proprietaire"), [
                                "expires" => time() + 10 * 365 * 24 * 60 * 60,
                                "path" => "/",
                            ]);
                        } else {
                            echo json_encode("failure");
                        }
                    }
                } else {
                    echo json_encode("preg_false");
                }

            } else if (checkData("email", $datas["email"]) && isset($_COOKIE["login"]) && checkData("email", $_COOKIE["login"]) && check_user($nom, $_COOKIE["login"]) === "admin") {
                echo json_encode("admin");
            }
            else {
                echo json_encode("danger");
            }

        } else {
            echo json_encode("danger");
        }
    } else {
        header("location: ../../Views/user/index.php");
    }

} else {
    header("location: ../../Views/user/index.php");
}

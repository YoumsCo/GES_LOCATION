<?php

namespace Models;
use \PDO;
use \PDOException;
use Dotenv\Dotenv;

abstract class DBConnexion
{
    protected static $pdo;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../");
        $dotenv->load();
        $host = $_ENV["DB_HOST"];
        $dbname = $_ENV["DB_NAME"];
        $port = $_ENV["DB_PORT"];
        $charset = $_ENV["DB_CHARSET"];
        $user = $_ENV["DB_USER"];
        $password = $_ENV["DB_PASSWORD"];

        $dsn = (string) 'mysql:host=' . $host . ';dbname=' . $dbname . ';port=' . $port . ';charset=' . $charset;

        try {
            // self::$pdo = new PDO($dsn, self::user, self::password);
            self::$pdo = new PDO($dsn, $user, $password);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'set names utf8');
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage() . '\nA la ligne : ' . $e->getLine());
        }
    }

    public static function getConnexion()
    {
        return self::$pdo;
    }
}
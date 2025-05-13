<?php

namespace Models;
use \PDO;
use \PDOException;

abstract class DBConnexion {
    private const host = $_ENV["HOST"];
    private const dbname = $_ENV["DN_NAME"];
    private const port = $_ENV["PORT"];
    private const charset = $_ENV["CHARSET"];
    private const user = $_ENV["USER"];
    private const password = $_ENV["PASSWORD"];
    private static $pdo;

    public function __construct() {
        $dsn = 'mysql:host='. self::host .';dbname='. self::dbname .';port='. self::port .';charset='. self::charset;

        try {
            self::$pdo = new PDO( $dsn, self::user, self::password );
            self::$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            self::$pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
            self::$pdo->setAttribute( PDO::MYSQL_ATTR_INIT_COMMAND, 'set names utf8' );
        } catch(PDOException $e ) {
            die( 'Erreur : '. $e->getMessage() . '\nA la ligne : '. $e->getLine() );
        }
    }

    public static function getConnexion() {
        return self::$pdo;
    }
}
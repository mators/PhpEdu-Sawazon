<?php

namespace db;

use \PDO;


/**
 * PDO singleton.
 * @package db
 */
class DBPool
{

    /**
     * @var PDO
     */
    private static $pdo;

    /**
     * Singleton private constructor.
     */
    private function __construct() {}

    /**
     * Returns PDO instance.
     * @return PDO
     */
    public static function getInstance()
    {
        if (null === self::$pdo) {
            try {
                self::$pdo = new PDO("mysql:dbname=sawazon_db", "root", "root", [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                ]);
            } catch (\PDOException $e) {
                var_dump($e);
                die();
            }
        }

        return self::$pdo;
    }

}

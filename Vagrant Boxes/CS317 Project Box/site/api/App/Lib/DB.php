<?php

namespace App\Lib;

use PDO;
use PDOException;

class DB {

    private static $db_instance = null;
    private PDO $pdo_instance;

    private function __construct() {

            $db_host = Config::get_config_option('DB_HOST','');
            $db_user = Config::get_config_option('DB_USER','');
            $db_pass = Config::get_config_option('DB_PASSWD','');
            $db_database = Config::get_config_option('DB_DATABASE','');
            $db_charset = Config::get_config_option('DB_CHARSET','');
            $db_options = Config::get_config_option('DB_OPTIONS',[]);
            $dsn = "mysql:host=" . $db_host . ";dbname=" . $db_database . ";charset=" . $db_charset;
            try {
                $this->pdo_instance = new PDO($dsn, $db_user, $db_pass, $db_options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), $e->getCode());
            }
    }

    public static function getInstance()
    {
        if (is_null(self::$db_instance)) {
            self::$db_instance = new DB();
        }
        return self::$db_instance;
    }

    public function run($sql,$args = null)
    {
//        echo $sql;
        if (!$args) {
            return $this->pdo_instance->query($sql);
        }
        $stmt = $this->pdo_instance->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    public function runBound($sql, $args = null)
    {
        if (!$args) {
            $this->run($sql);
        }
        $stmt = $this->pdo_instance->prepare($sql);
        foreach ($args as $param => $settings) {
            $stmt->bindParam($param,$settings['value'],$settings['type']);
         }
        $stmt->execute();
        return $stmt;
    }

}


<?php

namespace App\Lib;

use PDO;
use PDOException;

class DB {

    public $pdo;

    private $db_host;
    private $db_user;
    private $db_pass;
    private $db_database;
    private $db_charset;
    private $db_options;

    public function __construct()
    {
        $this->db_host = Config::get_config_option('DB_HOST','');
        $this->db_user = Config::get_config_option('DB_USER','');
        $this->db_pass = Config::get_config_option('DB_PASSWD','');
        $this->db_database = Config::get_config_option('DB_DATABASE','');
        $this->db_charset = Config::get_config_option('DB_CHARSET','');
        $this->db_options = Config::get_config_option('DB_OPTIONS',[]);

        $dsn = "mysql:host=".$this->db_host.";dbname=".$this->db_database .";charset=".$this->db_charset;
        try {
            $this->pdo = new PDO($dsn, $this->db_user, $this->db_pass, $this->db_options);
        }
        catch (PDOException $e)
        {
            throw new PDOException($e->getMessage(), $e->getCode());
        }
    }

    public function __destruct()
    {
        $this->pdo = null;
    }
    public function run($sql,$args = null)
    {
//        echo $sql;
        if (!$args) {
            return $this->pdo->query($sql);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    public function runBound($sql, $args = null)
    {
        if (!$args) {
            $this->run($sql);
        }
        $stmt = $this->pdo->prepare($sql);
        foreach ($args as $param => $settings) {
            $stmt->bindParam($param,$settings['value'],$settings['type']);
         }
        $stmt->execute();
        return $stmt;
    }

}


<?php

namespace App\Models;

use App\Lib\Config;
use App\Lib\DB;

class Genre {

    private $genre_id;
    private $genre_name;


    private $db_connection;

    public function __construct($id = null) {
        $this->db_connection = new DB();
        $this->genre_id = $id;
        if (is_null($id))
        {
            $this->genre_id = $this->find_last_id() + 1;
        }
        $this->load_genre($id);
    }

    public function getGenreName()
    {
        return $this->genre_name;
    }


    private function load_genre($id) {
        $sql = 'SELECT genre_name FROM Genre WHERE genre_id = ?';
        $stmt = $this->db_connection->run($sql,[$id]);
        $results = $stmt->fetch();
        if ($results !== false) {
            $this->genre_name = $results['genre_name'];
        }

    }

    private function find_last_id()
    {
        $sql = 'SELECT genre_id FROM Genre ORDER BY genre_id DESC LIMIT 1;';
        $stmt = $this->db_connection->run($sql);
        return $stmt->fetch()["genre_id"];
    }


}
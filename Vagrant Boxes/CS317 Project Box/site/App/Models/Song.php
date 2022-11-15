<?php

namespace App\Models;

use App\Lib\DB;
use PDO;

class Song
{
    private $song_id;
    private $song_name;
    private $artist_id;
    private $duration;
    private $db_connection;

    public function __construct($id) {
        $this->db_connection = new DB();
        $this->song_id = $id;
        if (is_null($id))
        {
            $this->song_id = $this->find_last_id() + 1;
        }
        $this->load_song();
    }

    public function getSongName()
    {
        return $this->song_name;
    }

    public function getArtistID()
    {
        return $this->artist_id;
    }

    public function getDuration() {
        return $this->duration;
    }

    public static function search($song_name) {
        $db_connection = new DB();
        $sql = "SELECT song_id FROM Song WHERE song_name LIKE BINARY ?";
        $stmt = $db_connection->run($sql,["%$song_name%"]);
        return $stmt->fetchAll();
    }
    private function find_last_id()
    {
        $sql = 'SELECT song_id FROM Song ORDER BY artist_id DESC LIMIT 1;';
        $stmt = $this->db_connection->run($sql);
        return $stmt->fetch()["song_id"];
    }

    private function load_song()
    {
        $sql = 'SELECT song_name,artist_id,duration FROM Song WHERE song_id = ?';
        $stmt = $this->db_connection->run($sql,[$this->song_id]);
        $results = $stmt->fetch();
        if ($results !== false)
        {
            $this->song_name = $results['song_name'];
            $this->artist_id = $results['artist_id'];
            $this->duration = $results['duration'];
        }
    }


}
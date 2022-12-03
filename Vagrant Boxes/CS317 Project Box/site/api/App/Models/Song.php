<?php

namespace App\Models;

use App\Lib\DB;
use App\Lib\Levenshtein;
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
//        var_dump($song_name);
        $db_connection = new DB();
        $distances = [];
        $sql = "SELECT
                    song_id AS id,
                    song_name AS search_result,
                    MATCH(song_name) AGAINST(:name) AS relevance
                    FROM Song
                    WHERE MATCH(song_name) AGAINST(:name2)
                    ORDER BY relevance DESC LIMIT 20";
        $stmt = $db_connection->run($sql,[':name' => $song_name,":name2"=>$song_name]);
        $top_20_results = $stmt->fetchAll();
//        var_dump($top_20_results);
        for ($i = 0; $i < sizeof($top_20_results); $i++) {
            $str_dist = Levenshtein::levenshtein_utf8(strtolower($top_20_results[$i]["search_result"]),strtolower($song_name));
            if ($str_dist != 255) {
                $distances[$top_20_results[$i]["id"]] = $str_dist;
            }
        }
        asort($distances);
//        var_dump($distances);
        return array_keys($distances);
    }
    private function find_last_id()
    {
        $sql = 'SELECT song_id FROM Song ORDER BY artist_id DESC LIMIT 1;';
        $stmt = $this->db_connection->run($sql);
        return $stmt->fetch()["song_id"];
    }

    private function load_song()
    {
//        $sql = 'SELECT song_name,artist_id,duration FROM Song WHERE song_id = ?';
        $sql = 'CALL get_song_model(?)';
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
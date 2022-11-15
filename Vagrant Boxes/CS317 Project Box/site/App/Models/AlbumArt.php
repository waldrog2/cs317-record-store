<?php

namespace App\Models;

use App\Lib\DB;

class AlbumArt
{
    public function __construct($id)
    {
        $this->db_connection = new DB();
        $this->artist_id = $id;
        if (is_null($id))
        {
            $this->artist_id = $this->find_last_id() + 1;
        }
        $this->load_artist();
    }

    public function getArtistName()
    {
        return $this->artist_name;
    }

    public function getGenreName()
    {
        return $this->genre_name;
    }

    private function find_last_id()
    {
        $sql = 'SELECT artist_id FROM Artist ORDER BY artist_id DESC LIMIT 1;';
        $stmt = $this->db_connection->run($sql);
        return $stmt->fetch()["artist_id"];
    }

    private function load_artist()
    {
        $sql = 'SELECT artist_name,genre_name FROM Artist INNER JOIN Genre ON Artist.genre_id = Genre.genre_id WHERE artist_id = ?';
        $stmt = $this->db_connection->run($sql,[$this->artist_id]);
        $results = $stmt->fetch();
        $this->artist_name = $results['artist_name'];
        $this->genre_name = $results['genre_name'];
    }
}
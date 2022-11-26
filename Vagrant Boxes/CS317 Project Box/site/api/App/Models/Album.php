<?php

namespace App\Models;

use App\Lib\DB;

class Album
{
    private $db_connection;
    private $album_id;
    private $album_name;
    private $artist_id;
    private $genre_id;
    private $subgenre_id;
    private $art_id;
    private $release_date;

    public function __construct($id)
    {
        $this->db_connection = new DB();
        $this->album_id = $id;
        if (is_null($id))
        {
            $this->album_id = $this->find_last_id() + 1;
        }
        $this->load_album();
    }

    public function getAlbumName()
    {
        return $this->album_name;
    }

    public function getArtistID()
    {
        return $this->artist_id;
    }

    public function getGenreID()
    {
        return $this->genre_id;
    }

    public function getSubgenreID()
    {
        return $this->subgenre_id;
    }

    public function getArtID()
    {
        return $this->art_id;
    }

    public function getReleaseDate()
    {
        return $this->release_date;
    }

    public function search($album_name)
    {

    }

    public static function getAlbumCount()
    {
        $db_connection = new DB();
        $sql = 'SELECT COUNT(DISTINCT album_id) AS album_count FROM Album';
        $stmt = $db_connection->run($sql);
        return $stmt->fetch()['album_count'];
    }

    public static function getNewestAlbums($count): array
    {
        $new_albums = [];
        $db_connection = new DB();
        $sql = 'SELECT album_id FROM Album GROUP BY artist_id ORDER BY release_date DESC LIMIT ?';
        $stmt = $db_connection->run($sql,[$count]);
//        var_dump($stmt->fetchAll());
        foreach ($stmt->fetchAll() as $new_album)
        {
            $new_albums[] = new Album($new_album['album_id']);
        }
//        var_dump($new_albums[0]);
        return $new_albums;
    }


    public function getRandom()
    {
        $sql = 'SELECT album_id FROM Album ORDER BY RAND() LIMIT 1';
        $stmt = $this->db_connection->run($sql);
        $this->album_id = $stmt->fetch()["album_id"];
        $this->load_album();
    }
    private function find_last_id()
    {
        $sql = 'SELECT album_id FROM Album ORDER BY album_id DESC LIMIT 1';
        $stmt = $this->db_connection->run($sql);
        return $stmt->fetch()["customer_id"];
    }


    private function load_album()
    {
        $sql = 'SELECT album_name,artist_id,genre_id,subgenre_id,art_id,release_date FROM Album WHERE album_id = ?';
//        echo $this->album_id;
        $stmt = $this->db_connection->run($sql,[$this->album_id]);
        $results = $stmt->fetch();
        if ($results !== false)
        {
            $this->album_name = $results['album_name'];
            $this->artist_id = $results['artist_id'];
            $this->genre_id = $results['genre_id'];
            $this->subgenre_id = $results['subgenre_id'];
            $this->art_id = $results['art_id'];
            $this->release_date = $results['release_date'];
        }
    }
}
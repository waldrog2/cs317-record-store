<?php

namespace App\Models;

use App\Lib\DB;

class AlbumSong
{
    private $db_connection;
    private $album_id;
    private $song_id;
    private $song_list = [];
    private $album_list = [];


    public function __construct($id, $type)
    {
        $this->db_connection = DB::getInstance();
        if ($type === "album") {
            $this->album_id = $id;
            $this->load_album_track_ids();
        } elseif ($type === "song") {
            $this->song_id = $id;
            $this->load_albums_song_appears_on();
        }
    }

    public function getTrackIDList()
    {
        return $this->song_list;
    }

    public function getAlbumIDList()
    {
        return $this->album_list;
    }

    private function load_album_track_ids()
    {
        $sql = 'SELECT DISTINCT(song_id) FROM AlbumSong WHERE album_id = ?';
        $stmt = $this->db_connection->run($sql, [$this->album_id]);
        $results = $stmt->fetchAll();
        if ($results !== false) {
            foreach ($results as $result) {
                $this->song_list[] = $result['song_id'];
            }
        }
    }

    private function load_albums_song_appears_on()
    {

        $sql = 'SELECT album_id,format_id FROM AlbumSong WHERE song_id = ?';
        $stmt = $this->db_connection->run($sql, [$this->song_id]);
        $results = $stmt->fetchAll();
        if ($results !== false) {
            foreach ($results as $result) {
                $this->album_list[$result['album_id']][] = $result['format_id'];
            }
        }
    }


}
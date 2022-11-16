<?php

namespace App\Models;

use App\Lib\DB;

class AlbumSong
{
    private $db_connection;
     private $album_id;
     private $song_id;
     private $format_id;
     private $song_list = [];
     private $album_list = [];


    public function __construct($id,$type) {
        $this->db_connection = new DB();
        if ($type === "album")
        {
            $this->album_id = $id;
        }
        elseif($type === "song")
        {
            $this->song_id = $id;
        }
        $this->load_albumsong();
    }

    public function load_albumsong()
    {
        if (!is_null($this->album_id))
        {
               $sql = 'SELECT song_id,format_id FROM AlbumSong WHERE album_id = ?';
               $stmt = $this->db_connection->run($sql,[$this->album_id]);
               $results = $stmt->fetchAll();
               if ($results !== false)
               {
                   foreach ($results as $result)
                   {
                       array_push($this->song_list,$result['song_id']);
                   }
               }
        }
        elseif (!is_null($this->song_id))
        {
            $sql = 'SELECT album_id,format_id FROM AlbumSong WHERE album_id = ?';
            $stmt = $this->db_connection->run($sql,[$this->album_id]);
            $results = $stmt->fetchAll();
            if ($results !== false)
            {
                foreach ($results as $result)
                {
                    array_push($this->song_list,$result['song_id']);
                }
            }
        }
    }

}
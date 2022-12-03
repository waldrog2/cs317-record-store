<?php

namespace App\Models;

use App\Lib\DB;
use App\Lib\ResizedImage;

class AlbumArt
{
    private $db_connection;
    private $art_id;
    private $art_path;

    public function __construct($id)
    {
        $this->db_connection = new DB();
        $this->art_id = $id;
        if (is_null($id))
        {
            $this->art_id = $this->find_last_id() + 1;
        }
        $this->load_art();
    }

    public function getArtPath()
    {
        return $this->art_path;
    }

    public function getArtLink()
    {
        $info = pathinfo($this->art_path);
        return "http://localhost:8044/album_art/". $info['filename'] . ".jpg";
    }

    public function encodeImage(): ?string
    {
        if (!is_null($this->art_path))
        {
            $resized_image = new ResizedImage($this->art_path,160,160);
            $art_data = $resized_image->getAsBase64();
            return 'data:image/jpeg;base64,' . base64_encode($art_data);
        }
        return null;
    }

    private function find_last_id()
    {
        $sql = 'SELECT art_id FROM AlbumArt ORDER BY art_id DESC LIMIT 1;';
        $stmt = $this->db_connection->run($sql);
        return $stmt->fetch()["art_id"];
    }

    private function load_art()
    {
//        $sql = 'SELECT path FROM AlbumArt WHERE art_id = ?';
        $sql = 'CALL get_art_model(?)';
        $stmt = $this->db_connection->run($sql,[$this->art_id]);
        $results = $stmt->fetch();
        if ($results !== false) {
//            var_dump($results['path']);
            $this->art_path = $results['path'];
        }
    }


}
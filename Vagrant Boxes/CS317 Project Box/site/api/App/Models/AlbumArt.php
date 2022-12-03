<?php

namespace App\Models;

use App\Lib\DB;

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

    public function encodeImage(): ?string
    {
        if (!is_null($this->art_path))
        {
            $art_data = file_get_contents($this->art_path);
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
        $sql = 'SELECT path FROM AlbumArt WHERE art_id = ?';
        $stmt = $this->db_connection->run($sql,[$this->art_id]);
        $results = $stmt->fetch();
        if ($results !== false) {
//            var_dump($results['path']);
            $this->art_path = $results['path'];
        }
    }


}
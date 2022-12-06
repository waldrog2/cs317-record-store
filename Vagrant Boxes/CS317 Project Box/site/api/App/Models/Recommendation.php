<?php

namespace App\Models;

use App\Lib\DB;

class Recommendation
{
    private $subgenre_id;
    private $db_connection;


    public function __construct($subgenre_id)
    {
        $this->subgenre_id = $subgenre_id;
        $this->db_connection = DB::getInstance();
    }

    public function getRecommendation($limit = 5)
    {
        $sql = "SELECT
                    album_id
                FROM
                    Album
                WHERE 
                    subgenre_id = ?
                ORDER BY 
                    RAND()
                LIMIT ?";
        $stmt = $this->db_connection->run($sql,[$this->subgenre_id,$limit]);
        $results = $stmt->fetchAll();
        $recommended_ids = [];
        foreach ($results as $album)
        {
            $recommended_ids[] = $album['album_id'];
        }
        return $recommended_ids;
    }
}
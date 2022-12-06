<?php

namespace App\Models;

use App\Lib\DB;

class Inventory
{
    private $item_id;
    private $album_id;
    private $price;
    private $stock;


    private $db_connection;

    public function __construct($id = null) {
        $this->db_connection = DB::getInstance();
        $this->item_id = $id;
        if (is_null($id))
        {
            $this->item_id = $this->find_last_id() + 1;
        }
        $this->load_item($id);
    }

    public function getAlbumID()
    {
        return $this->album_id;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function sellItem()
    {
        $sql = 'UPDATE Inventory SET stock = ?';
        $this->db_connection->run($sql,[$this->stock - 1]);
    }

    private function load_item($id) {
        $sql = 'SELECT album_id,price,stock FROM Inventory WHERE item_id = ?';
        $stmt = $this->db_connection->run($sql,[$id]);
        $results = $stmt->fetch();
        if ($results !== false) {
            $this->album_id = $results['album_id'];
            $this->price = $results['price'];
            $this->stock = $results['stock'];

        }

    }

    private function find_last_id()
    {
        $sql = 'SELECT item_id FROM Inventory ORDER BY item_id DESC LIMIT 1;';
        $stmt = $this->db_connection->run($sql);
        return $stmt->fetch()["item_id"];
    }
}
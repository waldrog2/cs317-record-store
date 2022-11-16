<?php

namespace App\Models;

use App\Lib\DB;

class Purchase
{
    private $db_connection;
    private $purchase_id;
    private $customer_id;
    private $purchased_item_id;
    private $purchased_date;


    public function __construct($id)
    {
        $this->db_connection = new DB();
        $this->purchase_id = $id;
        if (is_null($id))
        {
            $this->purchase_id = $this->find_last_id() + 1;
        }
        $this->load_purchase();
    }

    public function getPurchasedItemID()
    {
        return $this->purchased_item_id;
    }

    public function getCustomerID()
    {
        return $this->customer_id;
    }

    public function getPurchasedDate()
    {
        return $this->purchased_date;
    }

    private function find_last_id()
    {
        $sql = 'SELECT purchase_id FROM Purchase ORDER BY purchase_id DESC LIMIT 1;';
        $stmt = $this->db_connection->run($sql);
        return $stmt->fetch()["purchase_id"];
    }

    private function load_purchase()
    {
        $sql = 'SELECT customer_id,purchased_item_id, purchased_date FROM Album WHERE purchase_id = ?';
        $stmt = $this->db_connection->run($sql,[$this->purchase_id]);
        $results = $stmt->fetch();
        if ($results !== false)
        {
            $this->customer_id = $results['customer_id'];
            $this->purchased_item_id = $results['purchased_item_id'];
            $this->purchased_date = $results['purchased_date'];

        }
    }
}
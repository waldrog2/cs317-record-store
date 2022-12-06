<?php

namespace App\Models;

use App\Lib\DB;

class Customer
{
    private $db_connection;
    private $customer_id;
    private $cart_id;

    public function __construct($id)
    {
        $this->db_connection = DB::getInstance();
        $this->customer_id = $id;
        if (is_null($id))
        {
            $this->customer_id = $this->find_last_id() + 1;
        }
        $this->load_customer();
    }


    public function getCartID()
    {
        return $this->cart_id;
    }

    private function find_last_id()
    {
        $sql = 'SELECT customer_id FROM Customer ORDER BY customer_id DESC LIMIT 1;';
        $stmt = $this->db_connection->run($sql);
        return $stmt->fetch()["customer_id"];
    }

    private function load_customer()
    {
        $sql = 'SELECT cart_id FROM Customer WHERE customer_id = ?';
        $stmt = $this->db_connection->run($sql,[$this->customer_id]);
        $results = $stmt->fetch();
        if ($results !== false)
        {
            $this->cart_id = $results['cart_id'];
        }
    }
}
<?php

namespace App\Models;

use App\Lib\DB;

class Sessions
{
    private $db_connection;
    private $cart_id;
    private $cookie;

    public function __construct($id) {
        $this->db_connection = new DB();
        $this->cart_id = $id;
        if (is_null($id))
        {
            $this->cart_id = $this->find_last_id() + 1;
            $this->add_cookie_to_table();
        }
        $this->load_cart();
    }

    public function getCookie()
    {
        return $this->cookie;
    }

    private function generateCookie()
    {
        try {
            $this->cookie = base64_encode(random_bytes(32));
        }
       catch (\Exception $e) {

        }
    }

    private function find_last_id()
    {
        $sql = 'SELECT cart_id FROM Cart ORDER BY cart_id DESC LIMIT 1;';
        $stmt = $this->db_connection->run($sql);
        return $stmt->fetch()["cart_id"];
    }

    public function load_cart()
    {
        $sql = 'SELECT cookie FROM Cart WHERE cart_id = ?';
        $stmt = $this->db_connection->run($sql,[$this->cart_id]);
        $results = $stmt->fetch();
        if ($results !== false)
        {
   $this->cookie = $results['cookie'];
        }
    }

    private function add_cookie_to_table()
    {
        $sql = 'INSERT INTO Cart (cookie) VALUES(?)';
        $this->generateCookie();
        $stmt = $this->db_connection->run($sql,[$this->cookie]);
        $stmt->fetch();
    }
}
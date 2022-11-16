<?php

namespace App\Models;

use App\Lib\DB;

class Format
{
    private $format_id;
    private $format_name;
    private $db_connection;

    public function __construct($id_or_name)
    {

        $this->db_connection = new DB();
        if (is_int($id_or_name)) {
            $this->format_id = $id_or_name;
            $this->load_format();
        }
        elseif (is_string($id_or_name)) {
            $this->format_name = $id_or_name;
            $this->format_id = $this->get_format_id_by_name();
        }
        elseif (is_null($id_or_name))
        {
            $this->format_id = $this->find_last_id() + 1;
        }

    }

    public function getFormatID()
    {
        return $this->format_id;
    }

    public function getFormatName()
    {
        return $this->format_name;
    }


    private function find_last_id()
    {
        $sql = 'SELECT format_id FROM `Format` ORDER BY format_id DESC LIMIT 1;';
        $stmt = $this->db_connection->run($sql);
        return $stmt->fetch()["format_id"];
    }

    private function load_format()
    {
        $sql = 'SELECT format_name FROM `Format` WHERE format_id = ?';
        $stmt = $this->db_connection->run($sql,[$this->format_id]);
        $results = $stmt->fetch();
        if ($results !== false) {
            $this->format_name = $results['format_name'];
        }
    }

    private function get_format_id_by_name()
    {
        $sql = 'SELECT format_id FROM `FORMAT WHERE format_id = ?`';
        $stmt = $this->db_connection->run($sql,[$this->format_name]);
        $result = $stmt->fetch();
        if ($result !== false) {
            return $result['format_id'];
        }

    }
}
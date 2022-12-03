<?php

    namespace App\Lib;

    class Response
    {
        private $status = 200;
        private $headers = [];

        public function add_header($key,$content)
        {
            $this->headers[$key]= $content;
        }

        public function send($status_code, $message) {
            header('HTTP/1.1 ' . $status_code . " " . $message);
            foreach($this->headers as $header)
            {
                header('HTTP/1.1 ' . $header['key'] . " " . $header['content']);
            }
        }

        public function sendJSON($data = [])
        {
            http_response_code(200);
            header('Content-Type: application/json');
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST');
            header("Access-Control-Allow-Headers: X-Requested-With");
            echo json_encode($data);
        }

        public function redirect($url,$permanent)
        {
            echo "Sending redirect";
            if (strncmp('cgi', PHP_SAPI, 3) === 0) {
                header(sprintf('Status: %03u', $permanent ? 301 : 302), true, $permanent ? 301 : 302);
              }
        
              header('Location: ' . $url, true, $permanent ? 301 : 302);
        }
    }
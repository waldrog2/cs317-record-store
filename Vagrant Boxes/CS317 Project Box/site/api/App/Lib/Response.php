<?php

    namespace App\Lib;

    class Response
    {
        private $status = 200;

        public function status(int $code) {
            $this->status = $code;
            return $this;
        }

        public function send() {
           http_response_code(400);
           echo "Bad Request";
        }

        public function toJSON($data = [])
        {
            http_response_code($this->status);
            header('Content-Type: application/json');
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
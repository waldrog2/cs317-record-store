<?php

namespace App\Lib;

class Request {
    public $params;
    public $reqMethod;
    public $contentType;

    public function __construct($params = [])
    {
        $this->params = $params;
        $this->reqMethod = trim($_SERVER['REQUEST_METHOD']);
        $this->contentType = !empty($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
    }

    public function getQuery()
    {
        if ($this->reqMethod !== 'GET')
        {
            return ''; //POST doesn't use query string
        }

        $query_params = [];
        foreach ($_GET as $key => $value) {
            $query_params[$key] = filter_input(INPUT_GET,$key,FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $query_params;
    }
    public function getBody()
    {
        if ($this->reqMethod !== 'POST')
        {
            return ''; //we support POST and GET, GET does not have a body
        }

        $body = [];
        foreach ($_POST as $key => $value) {
            $body[$key] = filter_input(INPUT_POST,$key,FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $body;
    }

    public function getJSON()
    {
        if ($this->reqMethod !== 'POST')
        {
            return [];
        }

        if (strcasecmp($this->contentType,'application/json') !== 0) {
            return [];
        }

        $content =trim(file_get_contents("php://input"));
        try {
            $decoded = json_decode($content,true,512,JSON_THROW_ON_ERROR);
            return $decoded;
        }
        catch (\JsonException $e)
        {
            return []; // if we can't decode the JSON, return [] and let the caller figure it out
        }
    }
}

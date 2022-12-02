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

    public function parseQueryString($query_string): array
    {
        $seperate_params_regex = "#&#";
        $extract_key_value_regex = "#=#";
        $params_list = preg_split($seperate_params_regex,$query_string);
        $param_value_data = [];
        foreach ($params_list as $param)
            {
                $param_parts = preg_split($extract_key_value_regex,$param);
                $param_value_data[$param_parts[0]] = urldecode($param_parts[1]);
            }
        return $param_value_data;
    }

    public function getQuery() : array|string
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
            $body[$key] = urldecode(filter_input(INPUT_POST,$key,FILTER_SANITIZE_SPECIAL_CHARS));
        }

        return $body;
    }

    public function getEndOfPath()
    {

    }
    public function getJSON() : array
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

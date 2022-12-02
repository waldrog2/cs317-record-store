<?php

namespace App\Controller;

use App\Lib\Request;
use App\Lib\Response;
use App\Models\Artist;

class SearchController
{

    public static function search($subpath,$query_url_fragment)
    {
//        var_dump($subpath);
        $r = new Request();
        $parameters = $r->parseQueryString($query_url_fragment);
        if (!array_key_exists('q',$parameters))
        {
            $resp = new Response();
            $resp->send(400,"Bad Request");
        }
        $artist_results = Artist::search();
//        var_dump($query_url_fragment);
    }
}
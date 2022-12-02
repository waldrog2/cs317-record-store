<?php

namespace App\Controller;

use App\Lib\Request;
use App\Lib\Response;
use App\Models\Album;
use App\Models\AlbumArt;
use App\Models\Recommendation;

class RecommendationController
{
    public static function recommend($subpath, $query_url_fragment)
    {
        $r = new Request();
        $parameters = $r->parseQueryString($query_url_fragment);
        if (!array_key_exists('id', $parameters)) {
            $resp = new Response();
            $resp->send(400, "Bad Request");
        }

        $recommendation = new Recommendation($parameters['id']);
        if (array_key_exists('limit',$parameters))
        {
            $recommendation_list = $recommendation->getRecommendation($parameters['limit']);
        }
        else
        {
            $recommendation_list = $recommendation->getRecommendation();
        }
        $recommended = [
            'items' => []
        ];
        foreach ($recommendation_list as $album_id)
        {
            $album = new Album($album_id);
            $art = new AlbumArt($album->getArtID());
            $recommended_album = [
                'name' => $album->getAlbumName(),
                'art_data' => $art->encodeImage(),
                'album_link' => 'http://localhost:8044/api/album/' . $album_id
            ];
          $recommended['items'][] = $recommended_album;
        }

        $response = new Response();
        $response->sendJSON($recommended);
    }
}
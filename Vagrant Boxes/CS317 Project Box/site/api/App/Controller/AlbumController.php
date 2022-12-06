<?php

namespace App\Controller;

use App\Lib\Request;
use App\Lib\Response;
use App\Models\Album;
use App\Models\AlbumArt;
use App\Models\Artist;
use App\Models\Genre;

class AlbumController
{

    public static function getAlbumInfo($sub_path,$query_url_fragment)
    {
        $req = new Request();
        $parameters = $req->parseQueryString($query_url_fragment);
        if (!array_key_exists('id',$parameters))
        {
            $resp = new Response();
            $resp->send(400,"Bad Request");
        }
        $album = new Album(intval($parameters['id']));
        if (is_null($album->getAlbumName()))
        {
            $res = new Response();
            $res->send(400,"Bad Request");
        }
        else {
            $artist = new Artist($album->getArtistID());
            $art = new AlbumArt($album->getArtID());
            $base_genre = new Genre($album->getGenreID());
            $sub_genre = new Genre($album->getSubgenreID());
            $release_date = $album->getReleaseDate();
            $album_json = [
                'album_name' => $album->getAlbumName(),
                'artist' => $artist->getArtistName(),
                'art_link' => $art->getArtLink(),
                'genre' => $base_genre->getGenreName(),
                'subgenre' => $sub_genre->getGenreName(),
                'release_date' => $release_date
            ];
            $res = new Response();
            $res->sendJSON($album_json);
        }
    }
}
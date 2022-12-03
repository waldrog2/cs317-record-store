<?php

namespace App\Controller;

use App\Lib\Response;
use App\Models\Album;
use App\Models\AlbumArt;
use App\Models\Artist;
use App\Models\Genre;

class AlbumController
{

    public static function getAlbumInfo($album_data,$query_params)
    {
        $album = new Album(intval($album_data[0]));
        if (is_null($album->getAlbumName()))
        {
            $res = new Response();
            $res->send(404,"Not Found");
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
                'art_link' => $art->encodeImage(),
                'genre' => $base_genre->getGenreName(),
                'subgenre' => $sub_genre->getGenreName(),
                'release_date' => $release_date
            ];
            $res = new Response();
            $res->sendJSON($album_json);
        }
    }
}
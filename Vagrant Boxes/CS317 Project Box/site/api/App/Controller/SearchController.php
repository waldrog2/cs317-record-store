<?php

namespace App\Controller;

use App\Lib\Request;
use App\Lib\Response;
use App\Models\Album;
use App\Models\AlbumArt;
use App\Models\Artist;
use App\Models\Song;

class SearchController
{

    public static function search($subpath, $query_url_fragment)
    {
        $r = new Request();
        $parameters = $r->parseQueryString($query_url_fragment);
        if (!array_key_exists('q', $parameters)) {
            $resp = new Response();
            $resp->send(400, "Bad Request");
        }
        $album_results = Album::search($parameters['q']);
        $song_results = Song::search($parameters['q']);

        $results = [
            'items' => [
                'albums' => [],
                'songs' => []
            ]
        ];
        foreach ($album_results as $album_id) {
            $album = new Album($album_id);
            $artist = new Artist($album->getArtistID());
            $art = new AlbumArt($album->getArtID());
            $album_data = [
                'name' => $album->getAlbumName(),
                'artist' => $artist->getArtistName(),
                'art_link' => $art->getArtLink(),
                'album_link' => "http://localhost:8044/api/album/" . $album_id
            ];
            $results['items']['albums'][] = $album_data;
        }

        foreach ($song_results as $song_id) {
            $song = new Song($song_id);
            $artist = new Artist($song->getArtistID());

            $song_data = [
                'name' => $song->getSongName(),
                'artist' => $artist->getArtistName(),
                'duration' => $song->getDuration()
            ];
            $results['items']['songs'][] = $song_data;
        }

        $response = new Response();
        $response->sendJSON($results);
    }
}
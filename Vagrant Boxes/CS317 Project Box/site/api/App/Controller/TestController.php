<?php

namespace App\Controller;

use App\Lib\Request;
use App\Models\Artist;
use App\Models\Genre;
use App\Lib\Response;
use App\Models\Song;

class TestController {

    public static function getLastGenreID()
    {
        $genre = new Genre();
        echo ($genre->getGenreName());
        echo($genre->getSubgenreName());
    }

    public static function getArtist()
    {
        $artist = new Artist(1);
        echo $artist->getArtistName();
        echo $artist->getGenreName();
    }

    public static function testSongSearch($subpath,$query_fragments)
    {

        $request = new Request();
        $params = $request->parseQueryString($query_fragments);
//        echo "Getting queries";
        if (!array_key_exists('song_name',$params))
        {
            var_dump($params);
//            echo "Bad Request";
            $res = new Response();
            $res->send(400,"Bad Request");
        }
        $song_name = $params['song_name'];
        $song_list = Song::search($song_name);
//        var_dump($song_list);
        $results = [
            'items' => []
        ];
        foreach($song_list as $song_id) {
//            var_dump($song_id);
            $song = new Song($song_id);
            $artist = new Artist($song->getArtistID());

            $song_data = [
                'name' => $song->getSongName(),
                'artist' => $artist->getArtistName(),
                'duration' => $song->getDuration()
            ];
            $results['items'][] = $song_data;
        }

        $response = new Response();
        $response->sendJSON($results);
    }

}
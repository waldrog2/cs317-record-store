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

    public static function testSongSearch(Request $request)
    {
        $params = $request->getQuery();
//        echo "Getting queries";
        if (!array_key_exists('song_name',$params))
        {
            $res = new Response();
            $res->status(400);
            $res->send();
        }
        $song_name = $params['song_name'];
        $song_list = Song::search($song_name);
        $results = [
            'items' => []
        ];
        foreach($song_list as $result) {
            $song = new Song($result['song_id']);
            $artist = new Artist($song->getArtistID());

            $song_data = [
                'name' => $song->getSongName(),
                'artist' => $artist->getArtistName(),
                'duration' => $song->getDuration()
            ];
            $results['items'][] = $song_data;
        }

        $response = new Response();
        $response->status(200);
        $response->toJSON($results);
    }

}
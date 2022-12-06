<?php

namespace App\Controller;

use App\Lib\Request;
use App\Lib\Response;
use App\Lib\URLUtils;
use App\Models\Album;
use App\Models\AlbumArt;
use App\Models\Artist;

class GridController
{

    private static function cmp($a, $b)
    {
        return $a['artist'] <=> $b['artist'];
    }

    public static function getHomepageGrid()
    {

//        $req = new Request();
//        $parameters = $req->parseQueryString($query_url_fragment);
////            var_dump(array_key_exists('row_number',$parameters));
//        if (!array_key_exists('max', $parameters)) {
//            $resp = new Response();
//            $resp->send(400, "Bad Request");
//            exit();
//        }

//        if ($parameters['max'] == -1) {
//            $max_album_id = Album::getAlbumCount();
//        } else {
//            $max_album_id = $parameters['max'];
//        }
        $starting_album_id = 1;
        $max_album_id = Album::getAlbumCount();
        $ending_album_id = $max_album_id - 1;
//            $ending_album_id = 50;
        $grid_row_data = [
        ];

        $albums = Album::getAll();
        array_multisort(array_column($albums, 'artist_name'), SORT_ASC,
            array_column($albums, 'album_name'), SORT_ASC,
            $albums);

        $working_set = array_slice($albums, 0, $ending_album_id, true);


        foreach ($working_set as $album_data) {
            $timestamp = strtotime($album_data['release_date']);
            $formatted_timestamp = date("F j, Y", $timestamp);
            $grid_row_data['entries'][] = [
                'art_link' => URLUtils::artPathToURL($album_data['art_path']),
                'album_full_link' => URLUtils::generateAPIPath("albumPage",$album_data['album_id']),
                'album_full_data_link' => URLUtils::generateAPIPath("albumAPI",$album_data['album_id']),
                'title' => $album_data['album_name'],
                'artist' => $album_data['artist_name'],
                'release_date' => $formatted_timestamp
            ];
        }
        $resp = new Response();
        $resp->sendJSON($grid_row_data);

    }

    public static function getGenreGrid($subpath, $query_url_fragment)
    {

        $req = new Request();
        $parameters = $req->parseQueryString($query_url_fragment);
        if (!array_key_exists('genre', $parameters)) {
            $resp = new Response();
            $resp->send(400, "Bad Request");
            exit;
        }

        $grid_row_data = [
        ];

        $albums = Album::getAllOfGenre($parameters['genre']);
        array_multisort(array_column($albums, 'artist_name'), SORT_ASC,
            array_column($albums, 'album_name'), SORT_ASC,
            $albums);

        foreach ($albums as $album_data) {
            $timestamp = strtotime($album_data['release_date']);
            $formatted_timestamp = date("F j, Y", $timestamp);
            $grid_row_data['entries'][] = [
                'art_link' => URLUtils::artPathToURL($album_data['art_path']),
                'album_full_link' => URLUtils::generateAPIPath("albumPage",$album_data['album_id']),
                'album_full_data_link' => URLUtils::generateAPIPath("albumAPI",$album_data['album_id']),
                'title' => $album_data['album_name'],
                'artist' => $album_data['artist_name'],
                'release_date' => $formatted_timestamp
            ];
        }
        $resp = new Response();
        $resp->sendJSON($grid_row_data);
    }
}

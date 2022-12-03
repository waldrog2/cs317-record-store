<?php

    namespace App\Controller;

    use App\Lib\Request;
    use App\Lib\Response;
    use App\Models\Album;
    use App\Models\AlbumArt;
    use App\Models\Artist;

    class GridController {

        public static function getGridPage($matches,$params)
        {

            $req = new Request();
            $parameters = $req->parseQueryString($params);
//            var_dump(array_key_exists('row_number',$parameters));

//                echo "Here!";
                $max_album_id = Album::getAlbumCount();

                $starting_album_id = 1;
                $ending_album_id = $max_album_id - 1;

                $grid_row_data = [
                ];
                for ($album_id = $starting_album_id; $album_id < $ending_album_id; $album_id++)
                {
                    $album = new Album($album_id);
                    $artist = new Artist($album->getArtistID());
                    $album_art = new AlbumArt($album->getArtID());

                    $encoded_art = null;
                    if ($album_art->getArtPath() != null)
                    {
                        $art_data = file_get_contents($album_art->getArtPath());
                        $encoded_art = 'data:image/jpeg;base64,' . base64_encode($art_data);
                    }

                    $grid_row_data['entries'][] = [
                        'art_data' => $encoded_art,
                        'album_full_data_link' => '/api/album/' . $album_id,
                        'title' => $album->getAlbumName(),
                        'artist' => $artist->getArtistName()
                    ];
                }
                $resp = new Response();
                $resp->sendJSON($grid_row_data);
        }
    }

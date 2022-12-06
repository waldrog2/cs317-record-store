<?php

    namespace App\Controller;

    use App\Lib\Response;
    use App\Models\Album;
    use App\Models\AlbumArt;
    use App\Models\Artist;


    class HomeController {

        public static function getHomepage()
        {
            $homepage_data = [
                'featured_album' => [],
                'new_releases' => []
            ];
            $featuredItem = new Album(null);
            $featuredItem->getRandom();
            $artist = new Artist($featuredItem->getArtistID());
            $album_art = new AlbumArt($featuredItem->getArtID());
            $homepage_data['featured_album'] = [
                'title' => $featuredItem->getAlbumName(),
                'artist' => $artist->getArtistName(),
                'art_link' => $album_art->getArtLink()
            ];
            $newest_albums = Album::getNewestAlbums(10);
            foreach($newest_albums as $new_album) {
                $artist = new Artist($new_album->getArtistID());
                $album_art = new AlbumArt($new_album->getArtID());

                $homepage_data['new_releases'][] = [
                    'title' => $new_album->getAlbumName(),
                    'artist' => $artist->getArtistName(),
                    'art_link' => $album_art->getArtLink(),
                ];
            }
            $resp = new Response();
            $resp->sendJSON($homepage_data);

        }
    }
DELIMITER //;

CREATE PROCEDURE get_album_model (IN param1 INT)
 BEGIN
  SELECT SELECT album_name,artist_id,genre_id,subgenre_id,art_id,release_date FROM Album WHERE album_id = param1
 END;
//

CREATE PROCEDURE album_search(IN param1 VARCHAR)
 BEGIN
  SELECT
                    album_id AS id,
                    album_name AS search_result,
                    MATCH(album_name) AGAINST(param1) AS relevance
                    FROM Album
                    WHERE MATCH(album_name) AGAINST(param1)
                    ORDER BY relevance DESC LIMIT 20
 END;
//

CREATE PROCEDURE get_art_model(IN param1 INT)
 BEGIN
  SELECT path FROM AlbumArt WHERE art_id = param1
 END;
//


CREATE PROCEDURE song_search(IN param1 VARCHAR)
 BEGIN
  SELECT
                    song_id AS id,
                    song_name AS search_result,
                    MATCH(song_name) AGAINST(param1) AS relevance
                    FROM Song
                    WHERE MATCH(song_name) AGAINST(param1)
                    ORDER BY relevance DESC LIMIT 20
 END;
//

CREATE PROCEDURE get_song_model(IN param1 INT)
 BEGIN
  SELECT song_name,artist_id,duration FROM Song WHERE song_id = param1
 END;
//

CREATE PROCEDURE get_artist_model(IN param1 INT)
 BEGIN
  SELECT artist_name FROM Artist  WHERE artist_id = param1
 END;
//

CREATE PROCEDURE  get_album_list_by_genre (IN id INT)
 BEGIN
  SELECT
      album_id,album_name,artist_name,path,release_date
  FROM Album
      INNER JOIN AlbumArt ON Album.art_id = AlbumArt.art_id
     INNER JOIN Artist ON Album.artist_id = Artist.artist_id
  WHERE genre_id = id;
 END;
//

CREATE PROCEDURE  get_album_dump ()
 BEGIN
  SELECT
      album_id,album_name,artist_name,path,release_date
  FROM Album
      INNER JOIN AlbumArt ON Album.art_id = AlbumArt.art_id
     INNER JOIN Artist ON Album.artist_id = Artist.artist_id;
 END;
//

DELIMITER ;
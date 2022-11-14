-- SELECT 
--     sng.song_name,alb.album_name,art.artist_name,sng.duration,form.format_name
-- FROM
--     AlbumSong as albumsng
-- INNER JOIN 
--     Song as sng
-- ON
--     sng.song_id = albumsng.song_id
-- INNER JOIN 
--     Album as alb
-- ON
--     alb.album_id = albumsng.album_id
-- INNER JOIN 
--     Artist as art
-- ON
--     sng.artist_id = art.artist_id
-- INNER JOIN
--     `Format` as form
-- ON
--    form.format_id = albumsng.format_id 

-- WHERE albumsng.album_id BETWEEN 1 AND 15
-- ORDER BY
--     art.artist_name,alb.album_name,form.format_id
-- ASC;


SELECT
    alb.album_name, art.artist_name,albart.path
FROM
    Album as alb
INNER JOIN
    Artist as art
ON
    art.artist_id = alb.artist_id
INNER JOIN
    AlbumArt as albart
ON
    albart.art_id = alb.art_id
ORDER BY
    art.artist_name,alb.album_name
ASC
LIMIT 15
;


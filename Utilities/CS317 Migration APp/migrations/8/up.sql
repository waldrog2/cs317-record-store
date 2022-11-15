CREATE TABLE Album (
	album_id INT NOT NULL,
	album_name VARCHAR(256) NOT NULL,
	artist_id INT NOT NULL,
	art_id INT NOT NULL,
	release_date DATE NOT NULL,
	PRIMARY KEY (album_id),
	FOREIGN KEY (album_id) REFERENCES AlbumSong(album_id),
	FOREIGN KEY (artist_id) REFERENCES Artist(artist_id),
	FOREIGN KEY (art_id) REFERENCES AlbumArt(art_id)
);
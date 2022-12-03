CREATE TABLE Album (
	album_id INT NOT NULL AUTO_INCREMENT,
	album_name VARCHAR(512) CHARACTER SET utf8mb4 NOT NULL,
	artist_id INT NOT NULL,
	genre_id INT NOT NULL,
	subgenre_id INT NOT NULL,
	art_id INT NOT NULL,
	release_date DATE NOT NULL,
	PRIMARY KEY (album_id),
	FOREIGN KEY (artist_id) REFERENCES Artist(artist_id),
	FOREIGN KEY (art_id) REFERENCES AlbumArt(art_id),
	FOREIGN KEY (genre_id) REFERENCES Genre(genre_id)
);
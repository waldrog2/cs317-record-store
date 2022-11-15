CREATE TABLE Song (
	song_id INT NOT NULL AUTO_INCREMENT,
	song_name VARCHAR(1024) CHARSET utf8mb4 NOT NULL,
	artist_id INT NOT NULL,
	duration TIME NOT NULL,
	PRIMARY KEY (song_id),
	FOREIGN KEY (artist_id) REFERENCES Artist(artist_id)
);
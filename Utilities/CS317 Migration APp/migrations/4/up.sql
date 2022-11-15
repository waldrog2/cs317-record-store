CREATE TABLE Song (
	song_id INT NOT NULL AUTO_INCREMENT,
	song_name VARCHAR(256) NOT NULL,
	artist_id INT NOT NULL,
	duration TIME NOT NULL,
	PRIMARY KEY (song_id),
	FOREIGN KEY (artist_id) REFERENCES Artist(artist_id)
);
CREATE TABLE AlbumSong (
	album_id INT NOT NULL,
	song_id INT NOT NULL,
	format_id INT NOT NULL,
	PRIMARY KEY (album_id, song_id, format_id),
	FOREIGN KEY (album_id) REFERENCES Album(album_id),
	FOREIGN KEY (song_id) REFERENCES Song(song_id),
	FOREIGN KEY (format_id) REFERENCES `Format`(format_id)
);

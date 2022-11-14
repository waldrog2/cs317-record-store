CREATE TABLE AlbumDescription (
	album_id INT NOT NULL ,
	description VARCHAR(250) NOT NULL,
	PRIMARY KEY (album_id),
	FOREIGN KEY (album_id) REFERENCES Album(album_id)
);

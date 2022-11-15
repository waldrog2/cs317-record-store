CREATE TABLE Artist (
	artist_id INT NOT NULL AUTO_INCREMENT,
	artist_name VARCHAR(50) NOT NULL,
	genre_id INT NOT NULL,
	description VARCHAR(250) NOT NULL,
	PRIMARY KEY (artist_id),
	FOREIGN KEY (genre_id) REFERENCES Genre(genre_id)
);
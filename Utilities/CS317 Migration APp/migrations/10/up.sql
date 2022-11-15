CREATE TABLE Inventory (
	item_id INT NOT NULL AUTO_INCREMENT,
	album_id INT NOT NULL,
	price INT NOT NULL,
	stock INT NOT NULL,
	PRIMARY KEY (item_id),
	FOREIGN KEY (album_id) REFERENCES Album(album_id)
);
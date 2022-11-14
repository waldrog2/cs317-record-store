CREATE TABLE Customer (
	customer_id INT NOT NULL AUTO_INCREMENT,
	cart_id INT NOT NULL,
	PRIMARY KEY (customer_id),
	FOREIGN KEY (cart_id) REFERENCES Sessions(cart_id)
);
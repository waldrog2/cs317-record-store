CREATE TABLE Purchase (
	purchase_id INT NOT NULL AUTO_INCREMENT,
	customer_id INT NOT NULL,
	purchased_item_id INT NOT NULL,
	purchased_date DATETIME NOT NULL,
	PRIMARY KEY (purchase_id),
	FOREIGN KEY (customer_id) REFERENCES Customer(customer_id),
	FOREIGN KEY (purchased_item_id) REFERENCES Inventory(item_id)
);
CREATE TABLE IF NOT EXISTS repair_items
(
	itemId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	itemName VARCHAR(128) NOT NULL UNIQUE
) ENGINE = innodb;

INSERT INTO repair_items (itemName) VALUES
("Cell Phones"),
("Small Appliances"),
("Books"),
("Clothes"),
("Computers"),
("Furniture"),
("Lamps"),
("Lawn power equipment"),
("Outdoor Gear"),
("Sandals"),
("Shoes"),
("Boots"),
("Upholstery - Car"),
("Upholstery - Furniture");

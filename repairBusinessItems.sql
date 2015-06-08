CREATE TABLE IF NOT EXISTS rep_bus_items
(
	rId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	bId INT NOT NULL,
	iId INT NOT NULL,
	CONSTRAINT FOREIGN KEY (bid) REFERENCES repair_businesses (repairId) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT FOREIGN KEY (iId) REFERENCES repair_items (itemId) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO rep_bus_items (iId, bId) VALUES
((SELECT itemId FROM repair_items WHERE itemName = "Books"), (SELECT repairId FROM repair_businesses WHERE repairName = "Book binding")),
((SELECT itemId FROM repair_items WHERE itemName = "Cell Phones"), (SELECT repairId FROM repair_businesses WHERE repairName = "Cell Phone Sick Bay")),
((SELECT itemId FROM repair_items WHERE itemName = "Cell Phones"), (SELECT repairId FROM repair_businesses WHERE repairName = "Geeks 'N' Nerds")),
((SELECT itemId FROM repair_items WHERE itemName = "Clothes"), (SELECT repairId FROM repair_businesses WHERE repairName = "Specialty Sewing By Leslie")),
((SELECT itemId FROM repair_items WHERE itemName = "Computers"), (SELECT repairId FROM repair_businesses WHERE repairName = "Covallis Technical")),
((SELECT itemId FROM repair_items WHERE itemName = "Computers"), (SELECT repairId FROM repair_businesses WHERE repairName = "Bellevue Computers")),
((SELECT itemId FROM repair_items WHERE itemName = "Computers"), (SELECT repairId FROM repair_businesses WHERE repairName = "Geeks 'N' Nerds")),
((SELECT itemId FROM repair_items WHERE itemName = "Small Appliances"), (SELECT repairId FROM repair_businesses WHERE repairName = "OSU Repair Fair")),
((SELECT itemId FROM repair_items WHERE itemName = "Computers"), (SELECT repairId FROM repair_businesses WHERE repairName = "OSU Repair Fair")),
((SELECT itemId FROM repair_items WHERE itemName = "Furniture"), (SELECT repairId FROM repair_businesses WHERE repairName = "P.K Furniture Repair & Refinishing")),
((SELECT itemId FROM repair_items WHERE itemName = "Furniture"), (SELECT repairId FROM repair_businesses WHERE repairName = "Furniture Restoration Center")),
((SELECT itemId FROM repair_items WHERE itemName = "Lawn power equipment"), (SELECT repairId FROM repair_businesses WHERE repairName = "Power equipment")),
((SELECT itemId FROM repair_items WHERE itemName = "Lawn power equipment"), (SELECT repairId FROM repair_businesses WHERE repairName = "Robnett's")),
((SELECT itemId FROM repair_items WHERE itemName = "Sandles"), (SELECT repairId FROM repair_businesses WHERE repairName = "Footwise")),
((SELECT itemId FROM repair_items WHERE itemName = "Window and Door Screens"), (SELECT repairId FROM repair_businesses WHERE repairName = "Robnett's")),
((SELECT itemId FROM repair_items WHERE itemName = "Shoes"), (SELECT repairId FROM repair_businesses WHERE repairName = "Sedlack")),
((SELECT itemId FROM repair_items WHERE itemName = "Boots"), (SELECT repairId FROM repair_businesses WHERE repairName = "Sedlack")),
((SELECT itemId FROM repair_items WHERE itemName = "Upholstery - Furniture"), (SELECT repairId FROM repair_businesses WHERE repairName = "Foam Man"));


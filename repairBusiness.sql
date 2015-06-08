CREATE TABLE IF NOT EXISTS repair_businesses
(
	repairId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	repairName VARCHAR(250) NOT NULL,
	repairAddress VARCHAR(250) NOT NULL,
	repairCity VARCHAR(50) NOT NULL,
	repairState VARCHAR(20) NOT NULL,
	repairZip VARCHAR(10) NOT NULL,
	repairPhone VARCHAR(10) NOT NULL,
	repairWeb VARCHAR(255),
	repairHours VARCHAR(255),
	repairAddInfo TEXT(500),
	repairLongitude FLOAT(10, 6),
	repairLatitude FLOAT(10, 6)

) ENGINE = innodb;
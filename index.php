<?php
	
	require '../vendor/autoload.php';
	//\Slim\Slim::registerAutoloader();

	
	
	$app = new \Slim\Slim(array('debug' => true));
	$app->get('/', function() {
		echo "Hello world";
		
	});
	$app->get('/repair', function() {
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$result = $mysqli->query('SELECT repairName, repairAddress, repairCity, repairState, repairZip, repairPhone FROM repair_businesses');
		
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		echo json_encode($myArray);
		$result->close();
		$mysqli->close();
	});
	
	
	$app->get('/repair/:name', function($name) {
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("SELECT repairName, repairAddress, repairCity, repairState, repairZip, repairPhone FROM repair_businesses WHERE repairName = ?");
		$stmt->bind_param("s", $name);
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		echo json_encode($myArray);
		$result->close();
		$mysqli->close();
 	});
	
	
	$app->get('/repairItem', function() {
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$result = $mysqli->query('SELECT itemName FROM repair_items');
		
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		echo json_encode($myArray);
		$result->close();
		$mysqli->close();
	});
	
	$app->post('/repairItem', function() use ($app){
		
		$request = $app->request();
		$body = $request->getBody();
		$params = json_decode($body);
		
		$item = (string)$params->itemName;
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("INSERT INTO repair_items(itemName) VALUES (?)");
		$stmt->bind_param("s", $item);
		$stmt->execute();
		echo "Good";
	});
	
	$app->get('/repairItem/:item', function($item) {
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("SELECT repairName FROM repair_businesses AS rb INNER JOIN rep_bus_items AS rbi ON rb.repairId = rbi.bId INNER JOIN repair_items AS ri ON ri.itemId = rbi.iId WHERE ri.ItemName = ?");
		$stmt->bind_param("s", $item);
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		echo json_encode($myArray);
		$result->close();
		$mysqli->close();
	});
	
	$app->post('/repair', function() use ($app){
		
		$request = $app->request();
		$body = $request->getBody();
		$params = json_decode($body);
		
		$name = (string)$params->businessName;
		$address = (string)$params->Address;
		$city = (string)$params->city;
		$state = (string)$params->state;
		$zip = (string)$params->zip;
		$phone = (string)$params->phone;
		$website = (string)$params->website;
		$hours = (string)$params->hours;
		$addInfo = (string)$params->addInfo;
		$lat = (double)$params->lat;
		$long = (double)$params->long;
		
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("INSERT INTO repair_businesses(repairName, repairAddress, repairCity, repairState, repairZip, repairPhone, repairWeb, repairHours, repairAddInfo, repairLongitude, repairLatitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("sssssssssdd", $name, $address, $city, $state, $zip, $phone, $website, $hours, $addInfo, $lat, $long);
		$stmt->execute();
		$mysqli->close();
		echo "Good";
	});
	
	$app->delete('/repair/:name', function($name) use ($app){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("DELETE FROM repair_businesses WHERE repairName = ?");
		$stmt->bind_param("s", $name);
		$stmt->execute();
		$mysqli->close();
	});
	
	$app->delete('/repairItem/:item', function($item) use($app){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("DELETE FROM repair_items WHERE ItemName = ?");
		$stmt->bind_param("s", $item);
		$stmt->execute();
		$mysqli->close();
	});
	
	$app->delete('/repair/:name/:item', function($name, $item){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("DELETE FROM rep_bus_items WHERE (bId = (SELECT repairId FROM repair_businesses WHERE repairName = ?) AND iId = (SELECT itemId FROM repair_items WHERE ItemName = ?))");
		$stmt->bind_param("ss", $name, $item);
		$stmt->execute();
		$mysqli->close();
	});
	
	$app->put('/repair/:name/:item', function($name, $item){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("INSERT INTO rep_bus_items(bId, iId) VALUES((SELECT repairId FROM repair_businesses WHERE repairName = ?), (SELECT itemId FROM repair_items WHERE ItemName = ?))");
		$stmt->bind_param("ss", $name, $item);
		$stmt->execute();
		$mysqli->close();
	});
	

	$app->patch('/repair/:name', function($name) use($app){
		$request = $app->request();
		$body = $request->getBody();
		$params = json_decode($body);
		
		$name = (string)$params->businessName;
		$address = (string)$params->Address;
		$city = (string)$params->city;
		$state = (string)$params->state;
		$zip = (string)$params->zip;
		$phone = (string)$params->phone;
		$website = (string)$params->website;
		$hours = (string)$params->hours;
		$addInfo = (string)$params->addInfo;
		$lat = (double)$params->lat;
		$long = (double)$params->long;
		
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("UPDATE repair_businesses SET repairAddress=?, repairCity=?, repairState=?, repairZip=?, repairPhone=?, repairWeb=?, repairHours=?, repairAddInfo=?, repairLongitude=?, repairLatitude=?");
		$stmt->bind_param("ssssssssdds", $name, $address, $city, $state, $zip, $phone, $website, $hours, $addInfo, $lat, $long, $name);
		$stmt->execute();
		$mysqli->close();

	});
	
	$app->run();
?>

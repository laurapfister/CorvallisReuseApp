<?php
	
	require '../vendor/autoload.php';
	//\Slim\Slim::registerAutoloader();

	
	
	$app = new \Slim\Slim(array('debug' => true));
	$app->get('/', function() {
		echo "Hello world";
		
	});
	$app->get('/repair', function() {
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$result = $mysqli->query('SELECT repairName, repairAddress, repairCity, repairState, repairZip, repairPhone, repairHours, repairWeb, repairLongitude, repairLatitude FROM repair_businesses');
		
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		
		$result->close();
		$mysqli->close();
	});
	
	$app->get('/repair/:name', function($name) {
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("SELECT repairName, repairAddress, repairCity, repairState, repairZip, repairPhone, repairHours, repairWeb, repairAddInfo, repairLongitude, repairLatitude FROM repair_businesses WHERE repairName = ?");

		$name = $mysqli->real_escape_string($name);
		$stmt->bind_param("s", $name);
		$stmt->execute();
		$result = $stmt->get_result();
		
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		$result->close();
		$mysqli->close();
 	});
	
	
	$app->get('/repairItem', function() {
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$result = $mysqli->query('SELECT itemName FROM repair_items');
		
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		$result->close();
		$mysqli->close();
	});
	
	$app->post('/repairItem', function() use ($app){
		
		$request = $app->request();
		$body = $request->getBody();
		$params = json_decode($body);
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		
		if(isset($params->itemName)){
			$item = $mysqli->real_escape_string((string)$params->itemName);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		
		$stmt = $mysqli->prepare("INSERT INTO repair_items(itemName) VALUES (?)");
		$stmt->bind_param("s", $item);
		$stmt->execute();
	});
	
	$app->get('/repairItem/:item', function($item) {
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("SELECT repairName FROM repair_businesses AS rb INNER JOIN rep_bus_items AS rbi ON rb.repairId = rbi.bId INNER JOIN repair_items AS ri ON ri.itemId = rbi.iId WHERE ri.ItemName = ?");
		$item = $mysqli->real_escape_string($item);
		$stmt->bind_param("s", $item);
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		else{
			$myArray[] = NULL;
			echo json_encode($myArray);
		}
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

		if(isset($params->businessName)){
			$name = $mysqli->real_escape_string((string)$params->businessName);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->Address)){
			$address = $mysqli->real_escape_string((string)$params->Address);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->city)){
			$city = $mysqli->real_escape_string((string)$params->city);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->state)){
			$state = $mysqli->real_escape_string((string)$params->state);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->zip)){
			$zip = $mysqli->real_escape_string((string)$params->zip);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->phone)){
			$phone = $mysqli->real_escape_string((string)$params->phone);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->website)){
			$website = $mysqli->real_escape_string((string)$params->website);
		}
		if(isset($params->hours)){
			$hours = $mysqli->real_escape_string((string)$params->hours);
		}
		if(isset($params->addInfo)){
			$addInfo = $mysqli->real_escape_string((string)$params->addInfo);
		}
		if(isset($params->lat)){
			$lat = (double)$mysqli->real_escape_string((string)$params->lat);
		}
		if(isset($params->long)){
			$long = (double)$mysqli->real_escape_string((string)$params->long);
		}

		$stmt = $mysqli->prepare("INSERT INTO repair_businesses(repairName, repairAddress, repairCity, repairState, repairZip, repairPhone, repairWeb, repairHours, repairAddInfo, repairLongitude, repairLatitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("sssssssssdd", $name, $address, $city, $state, $zip, $phone, $website, $hours, $addInfo, $lat, $long);
		$stmt->execute();
		$mysqli->close();
		echo "Good";
	});
	
	$app->delete('/repair/:name', function($name){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");

		$name = $mysqli->real_escape_string($name);
		$stmt = $mysqli->prepare("DELETE FROM repair_businesses WHERE repairName = ?");
		$stmt->bind_param("s", $name);
		$stmt->execute();
		$mysqli->close();
	});
	
	$app->delete('/repairItem/:item', function($item){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$item = $mysqli->real_escape_string($item);
		$stmt = $mysqli->prepare("DELETE FROM repair_items WHERE ItemName = ?");
		$stmt->bind_param("s", $item);
		$stmt->execute();
		$mysqli->close();
	});
	
	$app->delete('/repair/:name/:item', function($name, $item){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("DELETE FROM rep_bus_items WHERE (bId = (SELECT repairId FROM repair_businesses WHERE repairName = ?) AND iId = (SELECT itemId FROM repair_items WHERE ItemName = ?))");

		$name = $mysqli->real_escape_string($name);
		$item = $mysqli->real_escape_string($item);
		$stmt->bind_param("ss", $name, $item);
		$stmt->execute();
		$mysqli->close();
	});
	
	$app->put('/repair/:name/:item', function($name, $item){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("INSERT INTO rep_bus_items(bId, iId) VALUES((SELECT repairId FROM repair_businesses WHERE repairName = ?), (SELECT itemId FROM repair_items WHERE ItemName = ?))");

		$name = $mysqli->real_escape_string($name);
		$item = $mysqli->real_escape_string($item);
		$stmt->bind_param("ss", $name, $item);
		$stmt->execute();
		$mysqli->close();
	});
	

	$app->patch('/repair/:name', function($name) use($app){
		$request = $app->request();
		$body = $request->getBody();
		$params = json_decode($body);

		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		
		if(isset($params->businessName)){
			$new_name = $mysqli->real_escape_string((string)$params->businessName);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->Address)){
			$address = $mysqli->real_escape_string((string)$params->Address);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->city)){
			$city = $mysqli->real_escape_string((string)$params->city);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->state)){
			$state = $mysqli->real_escape_string((string)$params->state);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->zip)){
			$zip = $mysqli->real_escape_string((string)$params->zip);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->phone)){
			$phone = $mysqli->real_escape_string((string)$params->phone);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->website)){
			$website = $mysqli->real_escape_string((string)$params->website);
		}
		if(isset($params->hours)){
			$hours = $mysqli->real_escape_string((string)$params->hours);
		}
		if(isset($params->addInfo)){
			$addInfo = $mysqli->real_escape_string((string)$params->addInfo);
		}
		if(isset($params->lat)){
			$lat = $mysqli->real_escape_string((string)$params->lat);
		}
		if(isset($params->long)){
			$long = $mysqli->real_escape_string((string)$params->long);
		}
		$name = $mysqli->real_escape_string($name);
		
		$stmt = $mysqli->prepare("UPDATE repair_businesses SET repairName = ? repairAddress=?, repairCity=?, repairState=?, repairZip=?, repairPhone=?, repairWeb=?, repairHours=?, repairAddInfo=?, repairLongitude=?, repairLatitude=? WHERE repairName = ?");
		$stmt->bind_param("ssssssssdds", $new_name, $address, $city, $state, $zip, $phone, $website, $hours, $addInfo, $lat, $long, $name);
		$stmt->execute();
		$mysqli->close();

	});

	$app->get('/reuse', function(){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$result = $mysqli->query('SELECT reuseName, reuseAddress, reuseCity, reuseState, reuseZip, reuseWeb, reuseHours, reuseLongitude, reuseLatitude FROM reuse_businesses');
		
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		$result->close();
		$mysqli->close();

	});

	$app->get('/reuse/:id', function($id){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("SELECT reuseName, reuseAddress, reuseCity, reuseState, reuseZip, reuseWeb, reuseHours, reuseLongitude, reuseLatitude FROM reuse_businesses WHERE reuseId = ?");

		$id = $mysqli->real_escape_string($id);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		$result->close();
		$mysqli->close();
	});

	$app->get('/reuseItems', function(){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$result = $mysqli->query('SELECT ri.itemName, rc.categoryName FROM reuse_items AS ri LEFT JOIN reuse_categories as rc ON rc.categoryId = ri.categoryId');
		
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		$result->close();
		$mysqli->close();
	});

	$app->get('/reuseCategory', function() {
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$result = $mysqli->query('SELECT categoryName FROM reuse_categories');
		
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		$result->close();
		$mysqli->close();
	});

	$app->get('/reuseItems/:category', function($category){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("SELECT ri.itemName FROM reuse_items AS ri INNER JOIN reuse_categories as rc ON rc.categoryId = ri.categoryId WHERE rc.categoryName = ?");

		$category = $mysqli->real_escape_string($category);
		$stmt->bind_param("s", $category);
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		$result->close();
		$mysqli->close();

	});

	$app->get('/reuseBus/:category', function($category){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("SELECT reuseName, reuseAddress, reuseCity, reuseState, reuseZip, reuseWeb FROM reuse_businesses AS rb INNER JOIN reuse_bus_categories AS rbc ON rbc.bid = rb.reuseId INNER JOIN reuse_categories as rc ON rc.categoryID = rbc.cid WHERE rc.categoryName = ?");
		$stmt->bind_param("s", $category);
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		$result->close();
		$mysqli->close();
	});

	$app->post('/reuse', function() use($app){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$request = $app->request();
		$body = $request->getBody();
		$params = json_decode($body);
			

		if(isset($params->reuseName)){
			$name = $mysqli->real_escape_string((string)$params->reuseName);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->reuseAddress)){
			$address = $mysqli->real_escape_string((string)$params->reuseAddress);
		}
		if(isset($params->state)){
			$state = $mysqli->real_escape_string((string)$params->state);
		}
		if(isset($params->city)){
			$city = $mysqli->real_escape_string((string)$params->city);
		}
		if(isset($params->zip)){
			$zip = $mysqli->real_escape_string((string)$params->zip);
		}
		if(isset($params->phone)){		
			$phone = $mysqli->real_escape_string((string)$params->phone);
		}
		if(isset($params->web)){
			$web = $mysqli->real_escape_string((string)$params->web);
		}
		if(isset($params->hours)){
			$hours = $mysqli->real_escape_string((string)$params->hours);
		}
		if(isset($params->longitude)){
			$long = (double)$mysqli->real_escape_string($params->longitude);
		}
		if(isset($params->latitude)){
			$lat = (double)$mysqli->real_escape_string($params->latitude);
		}

		$stmt = $mysqli->prepare("INSERT INTO reuse_businesses(reuseName, reuseAddress, reuseCity, reuseState, reuseZip, reusePhone, reuseWeb,reuseHours, reuseLongitude, reuseLatitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("ssssssssdd", $name, $address, $city, $state, $zip, $phone, $web, $hours, $long, $lat);
		$stmt->execute();
		$mysqli->close();
		
	});

	$app->post('/reuseItems', function() use ($app){

		$request = $app->request();
		$body = $request->getBody();
		$params = json_decode($body);
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");

		if(isset($params->itemName)){	
			$item = $mysqli->real_escape_string((string)$params->itemName);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->category)){
			$category = $mysqli->real_escape_string((string)$params->category);
		}
		$stmt = $mysqli->prepare("INSERT INTO reuse_items(itemName) VALUES(?)");
		$stmt->bind_param("s", $item);
		$stmt->execute();
	
		if(isset($category)){
			$stmt = $mysqli->prepare("UPDATE reuse_items SET categoryId = (SELECT categoryId FROM reuse_categories WHERE categoryName = ?) WHERE itemName = ?");
			$stmt->bind_param("ss", $category, $item);
			$stmt->execute();		
	
		}
		$mysqli->close();
		
	});

	$app->post('/reuseCategory', function() use($app){
		
		$request = $app->request();
		$body = $request->getBody();
		$params = json_decode($body);
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");

		if(isset($params->category)){	
			$category = $mysqli->real_escape_string((string)$params->category);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		$stmt = $mysqli->prepare("INSERT INTO reuse_categories(categoryName) VALUES(?)");
		$stmt->bind_param("s", $category);
		$stmt->execute();

		$mysqli->close();

		
	});

	$app->put('/reuse/:category/:business', function($category, $business){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");

		$category = $mysqli->real_escape_string($category);
		$business = (int)$mysqli->real_escape_string($business);
		
		$stmt = $mysqli->prepare("INSERT INTO reuse_bus_categories (cid, bid) VALUES((SELECT categoryId FROM reuse_categories WHERE categoryName = ?), ?)");
		$stmt->bind_param("si", $category, $business);
		$stmt->execute();
		$mysqli->close();
	

	});

	$app->delete('/reuse/:id', function($id){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$id = (int)$mysqli->real_escape_string($id);

		$stmt = $mysqli->prepare("DELETE FROM reuse_businesses WHERE reuseId = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$mysqli->close();
		
	
	});
	
	$app->delete('/reuseItems/:item', function($item){

		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$item = $mysqli->real_escape_string($item);

		$stmt = $mysqli->prepare("DELETE FROM reuse_items WHERE itemName = ?");
		$stmt->bind_param("s", $item);
		$stmt->execute();
		$mysqli->close();

	});

	$app->delete('/reuseCategory/:category', function($category){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$category = $mysqli->real_escape_string($category);

		$stmt = $mysqli->prepare("DELETE FROM reuse_categories WHERE categoryName = ?");
		$stmt->bind_param("s", $category);
		$stmt->execute();
		$mysqli->close();


	});

	$app->delete('/reuse/:category/:business', function($category, $business){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$category = $mysqli->real_escape_string($category);
		$business = (int)$mysqli->real_escape_string($business);
		
		$stmt = $mysqli->prepare("DELETE FROM reuse_bus_categories WHERE (bid = ? AND cid = (SELECT categoryId from reuse_categories WHERE categoryName = ?))");

		$stmt->bind_param("is", $business, $category);
		$stmt->execute();
		$mysqli->close(); 
	});
	

	
	$app->run();
?>

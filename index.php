<?php

	/*API for access to mysql Database calls
	  Allows access and manipulation to 
	    repair_businesses, repair_items
	    reuse_businesses, reuse_items, reuse_categories
	*/
	ini_set('display_errors', 1);	

	require '../vendor/autoload.php';
	require_once('../php-opencage-geocode/src/OpenCage.Geocoder.php');
	require '../oauth2-server-php/src/OAuth2/Autoloader.php';
	OAuth2\Autoloader::register();
	
	
	
		
	
	$app = new \Slim\Slim(array('debug' => true));
	$app->response->headers->set('Content-Type', 'application/json');
	
	/*Function to check if stored token exists, and is good. If not good returns an error to the users*/
	$check_token = function(\Slim\Route $route) use($app){
				session_start();
				if(!isset($_SESSION['token'])){
					$app->response->setStatus(401);
					echo "User Authentication required";
					exit(1);
				}
				$access_token = $_SESSION['token'];
		
				$curl = curl_init();
				curl_setopt_array($curl, array(
				CURLOPT_URL => 'http://web.engr.oregonstate.edu/~pfisterl/cs419/resource.php',
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array("access_token={$access_token}"),	
				CURLOPT_RETURNTRANSFER => 1
				));

				$resp = curl_exec($curl);
				$resp = json_decode($resp);
		
				if(isset($resp->{'error'})){
					$app->response->setStatus(401);
					echo "User Authentication required";
					exit(1);
				}
			};



	/*Returns an array of businesses in JSON format with the following information:
	    repairId - Id of business
    	    repairName - Name of Business
            repairAddress - Address of Business
            repairCity - City of Business
            repairState - state of Business
            reapirZip - zip code of Business 
            repairPhone - phone number of Business
            repairHours - hours of Business
            repairWeb - Website of Business
            repairLongitude - Longitude of Business
            repairLatitude - Latitude of Business
        */
	$app->get('/repair', function() {
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$result = $mysqli->query('SELECT repairId, repairName, repairAddress, repairCity, repairState, repairZip, repairPhone, repairHours, repairWeb, repairLongitude, repairLatitude FROM repair_businesses ORDER BY repairName ASC');
		
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		
		$result->close();
		$mysqli->close();
	});
	

	/*Returns a business in JSON format specified by and id number in the following format:
            repairId - Id of business
    	    repairName - Name of Business
            repairAddress - Address of Business
            repairCity - City of Business
            repairState - state of Business
            reapirZip - zip code of Business 
            repairPhone - phone number of Business
            repairHours - hours of Business
            repairWeb - Website of Business
            repairLongitude - Longitude of Business
            repairLatitude - Latitude of Business
        */
	$app->get('/repair/:id', function($id) {
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("SELECT repairId, repairName, repairAddress, repairCity, repairState, repairZip, repairPhone, repairHours, repairWeb, repairAddInfo, repairLongitude, repairLatitude FROM repair_businesses WHERE repairId = ?");

		$id = (int)$mysqli->real_escape_string($id);
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
	
	/*Returns All Items associated with the Business specified by Id in JSON format with the following info:
                itemId - the Id of the Item
                itemName - the Name of the Item
        */
	$app->get('/repair_busi_items/:id', function($id){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("SELECT ri.itemId, ri.itemName FROM repair_items AS ri INNER JOIN rep_bus_items AS rbi ON rbi.iId = ri.itemId WHERE rbi.bId = ? ORDER BY ri.itemName ASC");
		
		$id = (int)$mysqli->real_escape_string($id);
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

	/*Returns all Items in the repairItem table in JSON format in an array with the following info:
     	        itemId - the Id of the Item
                itemName - the Name of the Item
	*/
	$app->get('/repairItem', function() {
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$result = $mysqli->query('SELECT itemId, itemName FROM repair_items ORDER BY itemName ASC');
		
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		$result->close();
		$mysqli->close();
	});
	
	/*Add a new item to the repairItems database. Requires the following data in JSON format:
               itemName - name of items
	*/	
	$app->post('/repairItem', $check_token, function() use ($app){
		
		$request = $app->request();
		$body = $request->getBody();
		$params = json_decode($body);
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		
		
		$item = $request->params('itemName');
		/*if(isset($params->itemName)){
			$item = $mysqli->real_escape_string((string)$params->itemName);
		}
		else{
			echo "FALSE";
			$app->response->setStatus(400);
			exit(1);
		}*/
		
		$stmt = $mysqli->prepare("INSERT INTO repair_items(itemName) VALUES (?)");
		$stmt->bind_param("s", $item);
		$stmt->execute();
	});
	
	/*Returns all Businesses associated with an Item that is specified by the item id.
          Information is returned in JSON format with the following information:
                 repairId - Id of business
    	    repairName - Name of Business
            repairAddress - Address of Business
            repairCity - City of Business
            repairState - state of Business
            reapirZip - zip code of Business 
            repairPhone - phone number of Business
            repairHours - hours of Business
            repairWeb - Website of Business
            repairLongitude - Longitude of Business
            repairLatitude - Latitude of Business
 	*/	
	$app->get('/repairItem/:item', function($item) {
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("SELECT repairId, repairName, repairAddress, repairCity, repairState, repairZip, repairPhone, repairHours, repairWeb, repairAddInfo, repairLongitude, repairLatitude FROM repair_businesses AS rb INNER JOIN rep_bus_items AS rbi ON rb.repairId = rbi.bId INNER JOIN repair_items AS ri ON ri.itemId = rbi.iId WHERE ri.itemId = ? ORDER BY repairName ASC");
		$item = (int)$mysqli->real_escape_string($item);
		$stmt->bind_param("i", $item);
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


	
		
	/*Updates the a repair Item specified by an item id, in the first argument :item, and changes its name to :new_item*/
	
	$app->patch('/repairItem/:item/:new_item', $check_token, function($item, $new_item){

		
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("UPDATE repair_items SET itemName = ? WHERE itemId = ?");
		if(isset($params->itemName)){
			$new_item = $mysqli->real_escape_string((string)$params->itemName);
		}
		$stmt->bind_param("si", $new_item, $item);
		$stmt->execute();
		echo $item, $new_item;
	});
		
	/*Adds a new Business to the repair_businesses database. Data must be provided to in JSON format:
               businessName - name of Business
               Address - address of Business
               city - city of Business
               state - state of Business
               zip - zip code of Business
               phone - phone number of Business
               website - website of Business (optional)
               hours - hours of Business (optional)
               addInfo - Additional Business info (optional)
        */
	$app->post('/repair', $check_token, function() use ($app){
		
		$request = $app->request();
		$body = $request->getBody();
		$params = json_decode($body);
		
		
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
		if($address && $city && $state){
			$key = "39ee84f3ae0ca490055ca19becda2846";
			$geocoder = new OpenCage\Geocoder($key);
			$query = $address." ".$city.",".$state;
			$geo_res = $geocoder->geocode($query);
			$lat = $geo_res["results"][0]["geometry"]["lat"];
			$long = $geo_res["results"][0]["geometry"]["lng"];
		};


		$stmt = $mysqli->prepare("INSERT INTO repair_businesses(repairName, repairAddress, repairCity, repairState, repairZip, repairPhone, repairWeb, repairHours, repairAddInfo, repairLongitude, repairLatitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("sssssssssdd", $name, $address, $city, $state, $zip, $phone, $website, $hours, $addInfo, $lat, $long);
		$stmt->execute();
		echo $stmt->insert_id;
		$mysqli->close();

		
	});
	
	/*Deletes the business specified by repairId :id*/
	$app->delete('/repair/:id', $check_token, function($id){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");

		$id = $mysqli->real_escape_string($id);
		$stmt = $mysqli->prepare("DELETE FROM repair_businesses WHERE repairId = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$mysqli->close();
	});
	
	/*Deletes the item specified by :item, an itemId*/
	$app->delete('/repairItem/:item', $check_token, function($item){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$item = (int)$mysqli->real_escape_string($item);
		$stmt = $mysqli->prepare("DELETE FROM repair_items WHERE itemId = ?");
		$stmt->bind_param("i", $item);
		$stmt->execute();
		$mysqli->close();
	});
	
	/*Deletes the association between a business, specified by repairId: id, and an item specified by itemId: item*/
	$app->delete('/repair/:id/:item', $check_token, function($id, $item){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("DELETE FROM rep_bus_items WHERE (bId = ? AND iId = ?)");

		$id = (int)$mysqli->real_escape_string($id);
		$item = (int)$mysqli->real_escape_string($item);
		$stmt->bind_param("ii", $id, $item);
		$stmt->execute();
		$mysqli->close();
	});
	
	/*Adds as association between a business, specified by repairId: id, and an item specified by itemId: item*/
	$app->put('/repair/:id/:item', $check_token, function($id, $item){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("INSERT INTO rep_bus_items(bId, iId) VALUES(?, ?)");

		$id = (int)$mysqli->real_escape_string($id);
		$item = (int)$mysqli->real_escape_string($item);
		$stmt->bind_param("ii", $id, $item);
		$stmt->execute();
		$mysqli->close();
	});
	
	/*Updates a Business specified by repairId: id, to information provided in JSON data:
 		businessName - name of Business
               Address - address of Business
               city - city of Business
               state - state of Business
               zip - zip code of Business
               phone - phone number of Business
               website - website of Business (optional)
               hours - hours of Business (optional)
               addInfo - Additional Business info (optional)
	*/
	$app->patch('/repair/:id', $check_token, function($id) use($app){
		$request = $app->request();
		$body = $request->getBody();
		$params = json_decode($body);

		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		

		$new_name = $request->params('businessName');
		$address = $request->params('Address');
		$state = $request->params('state');
		$zip = $request->params('zip');
		$phone = $request->params('phone');
		
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
		
		if($address && $city && $state){
			$key = "39ee84f3ae0ca490055ca19becda2846";
			$geocoder = new OpenCage\Geocoder($key);
			$query = $address." ".$city.",".$state;
			$geo_res = $geocoder->geocode($query);
			$lat = $geo_res["results"][0]["geometry"]["lat"];
			$long = $geo_res["results"][0]["geometry"]["lng"];
		};
		$id = (int)$mysqli->real_escape_string($id);
		
		$stmt = $mysqli->prepare("UPDATE repair_businesses SET repairName = ?, repairAddress= ?, repairCity= ?, repairState= ?, repairZip= ?, repairPhone= ?, repairWeb= ?, repairHours= ?, repairAddInfo= ?, repairLongitude= ?, repairLatitude= ? WHERE repairId = ?");
		
		$stmt->bind_param("sssssssssddi", $new_name, $address, $city, $state, $zip, $phone, $website, $hours, $addInfo, $long, $lat, $id);
		$stmt->execute();
		$mysqli->close();
		

	});

	/*Returns an array of businesses in JSON format with the following information:
	    reuseId - Id of business
    	    reuseName - Name of Business
            reuseAddress - Address of Business
            reuseCity - City of Business
            reuseState - state of Business
            reuseZip - zip code of Business 
            reusePhone - phone number of Business
            reuseHours - hours of Business
            reuseWeb - Website of Business
            reuseLongitude - Longitude of Business
            reuseLatitude - Latitude of Business
        */
	$app->get('/reuse', function(){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$result = $mysqli->query('SELECT reuseId, reuseName, reuseAddress, reuseCity, reuseState, reuseZip, reusePhone, reuseWeb, reuseHours, reuseLongitude, reuseLatitude FROM reuse_businesses ORDER BY reuseName ASC');
		
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		$result->close();
		$mysqli->close();

	});
	/*Returns a business specified by reuseId: id in JSON format with the following information:
	    reuseId - Id of business
    	    reuseName - Name of Business
            reuseAddress - Address of Business
            reuseCity - City of Business
            reuseState - state of Business
            reuseZip - zip code of Business 
            reusePhone - phone number of Business
            reuseHours - hours of Business
            reuseWeb - Website of Business
            reuseLongitude - Longitude of Business
            reuseLatitude - Latitude of Business
        */
	$app->get('/reuse/:id', function($id){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("SELECT reuseId, reuseName, reuseAddress, reuseCity, reuseState, reuseZip, reusePhone, reuseWeb, reuseHours, reuseLongitude, reuseLatitude FROM reuse_businesses WHERE reuseId = ?");

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

	/*Returns all Items in reuse_items table in JSON in the following format:
		itemId - id of item
                itemName - name of item
                itemCategory - category specified by item
	*/
	$app->get('/reuseItems', function(){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$result = $mysqli->query('SELECT ri.itemId, ri.itemName, rc.categoryId, rc.categoryName FROM reuse_items AS ri LEFT JOIN reuse_categories as rc ON rc.categoryId = ri.categoryId ORDER BY ri.itemName ASC');
		
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		$result->close();
		$mysqli->close();
	});
	/*Returns all categories in reuse_categories table in an array of JSON in the following format:
		categoryId - id of category
		categoryName - name of category
	*/
	$app->get('/reuseCategory', function() {
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$result = $mysqli->query('SELECT categoryId, categoryName FROM reuse_categories ORDER BY categoryName ASC');
		
		while($row = $result->fetch_array(MYSQL_ASSOC)){
			$myArray[] = $row;
		}
		if(isset($myArray)){
			echo json_encode($myArray);
		}
		$result->close();
		$mysqli->close();
	});

	/*Returns all items under a reuse category specified by categoryId :category in JSON in the following format:
		itemId - id of item
                itemName - name of item
	*/
	$app->get('/reuseItems/:category', function($category){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("SELECT ri.itemId, ri.itemName FROM reuse_items AS ri INNER JOIN reuse_categories as rc ON rc.categoryId = ri.categoryId WHERE rc.categoryId = ? ORDER BY ri.itemName ASC");

		$category = (int)$mysqli->real_escape_string($category);
		$stmt->bind_param("i", $category);
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
	/*Returns all Businesses associated with categoryId :category in an array of JSON in the following format:
		reuseId - Id of business
    	    	reuseName - Name of Business
            	reuseAddress - Address of Business
            	reuseCity - City of Business
            	reuseState - state of Business
            	reuseZip - zip code of Business 
            	reusePhone - phone number of Business
            	reuseHours - hours of Business
            	reuseWeb - Website of Business
            	reuseLongitude - Longitude of Business
            	reuseLatitude - Latitude of Business
	*/
	$app->get('/reuseBus/:category', function($category){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("SELECT reuseId, reuseName, reuseAddress, reuseCity, reuseState, reuseZip, reusePhone, reuseWeb, reuseHours, reuseLongitude, reuseLatitude FROM reuse_businesses AS rb INNER JOIN reuse_bus_categories AS rbc ON rbc.bid = rb.reuseId INNER JOIN reuse_categories as rc ON rc.categoryId = rbc.cid WHERE rc.categoryId = ? ORDER BY rb.reuseName ASC");
		$stmt->bind_param("i", $category);
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
	/*Gets all categories associated with a business specified by id*/
	$app->get('/reuse_busi_cats/:id', function($id){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("SELECT rc.categoryName, rc.categoryId FROM reuse_categories as rc INNER JOIN reuse_bus_categories as rbc ON rbc.cid = rc.categoryId WHERE rbc.bid = ?");
		$id = (int)$mysqli->real_escape_string($id);
		$stmt->bind_param("i", $id);
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

	/*Adds a new Business to reuse_businesses table. Data is required in the following JSON format:
		reuseName - name of Business
 		reuseAddress - address of Business
 		state - state of business
		city - city of business
		zip - zip code of business
		phone - phone number of business
		web - website of business
		hours - hours of business
	*/	
	$app->post('/reuse', $check_token, function() use($app){
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
		if($address && $city && $state){
			$key = "39ee84f3ae0ca490055ca19becda2846";
			$geocoder = new OpenCage\Geocoder($key);
			$query = $address." ".$city.",".$state;
			if($geocoder->geocode($query) !== null){
				$geo_res = $geocoder->geocode($query);
				$lat = $geo_res["results"][0]["geometry"]["lat"];
				$long = $geo_res["results"][0]["geometry"]["lng"];
			}
		};

		$stmt = $mysqli->prepare("INSERT INTO reuse_businesses(reuseName, reuseAddress, reuseCity, reuseState, reuseZip, reusePhone, reuseWeb,reuseHours, reuseLongitude, reuseLatitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("ssssssssdd", $name, $address, $city, $state, $zip, $phone, $web, $hours, $long, $lat);
		$stmt->execute();
		echo $stmt->insert_id;
		$mysqli->close();
		
	});

	/*Add a new item to the reuse_items table. Data must be provided in JSON format as follows:
		itemName - name of new item
		category - name of category
	*/
	$app->post('/reuseItems', $check_token, function() use ($app){

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
		$stmt = $mysqli->prepare("INSERT INTO reuse_items(itemName, categoryId) VALUES(?, ?)");
		$stmt->bind_param("si", $item, $category);
		$stmt->execute();
		
		$mysqli->close();
		
	});
	/*Adds new category to the reuse_categories table. Data must be provided in JSON format as follows:
		category : name of new category
	*/
	$app->post('/reuseCategory', $check_token, function() use($app){
		
		$request = $app->request();
		$body = $request->getBody();
		$params = json_decode($body);
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");

		if(isset($params->categoryName)){	
			$category = $mysqli->real_escape_string((string)$params->categoryName);
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
	/*Creates new association between categoryId :category, and reuse_business: business*/
	$app->put('/reuse/:category/:business', $check_token, function($category, $business){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");

		$category = (int)$mysqli->real_escape_string($category);
		$business = (int)$mysqli->real_escape_string($business);
		
		$stmt = $mysqli->prepare("INSERT INTO reuse_bus_categories (cid, bid) VALUES(?, ?)");
		$stmt->bind_param("ii", $category, $business);
		$stmt->execute();
		$mysqli->close();
	

	});

	/*Deletes the business specified by reuseId : id from reuse_businesses table */
	$app->delete('/reuse/:id', $check_token, function($id){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$id = (int)$mysqli->real_escape_string($id);

		$stmt = $mysqli->prepare("DELETE FROM reuse_businesses WHERE reuseId = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$mysqli->close();
		
	
	});
	/*Deletes the items specified by :item from the reuse_items table*/
	$app->delete('/reuseItems/:item', $check_token, function($item){

		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$item = (int)$mysqli->real_escape_string($item);

		$stmt = $mysqli->prepare("DELETE FROM reuse_items WHERE itemId = ?");
		$stmt->bind_param("i", $item);
		$stmt->execute();
		$mysqli->close();

	});
	/*Deletes the category specified by :category from the reuse_items table*/
	$app->delete('/reuseCategory/:category', $check_token, function($category){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$category = (int)$mysqli->real_escape_string($category);

		$stmt = $mysqli->prepare("DELETE FROM reuse_categories WHERE categoryId = ?");
		$stmt->bind_param("i", $category);
		$stmt->execute();
		$mysqli->close();


	});
	/*Deletes the association between a category :category and repair business :business */
	$app->delete('/reuse/:category/:business', $check_token, function($category, $business){
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$category = (int)$mysqli->real_escape_string($category);
		$business = (int)$mysqli->real_escape_string($business);
		
		$stmt = $mysqli->prepare("DELETE FROM reuse_bus_categories WHERE (bid = ? AND cid = ?)");

		$stmt->bind_param("ii", $business, $category);
		$stmt->execute();
		$mysqli->close(); 
	});
	
	$app->patch('/reuse/:id', $check_token, function($id) use($app){
		$request = $app->request();
		$body = $request->getBody();
		$params = json_decode($body);

		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");


		$new_name = $request->params('businessName');
		$address = $request->params('Address');
		$state = $request->params('state');
		$zip = $request->params('zip');
		$phone = $request->params('phone');

		
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
		
		if(isset($params->city)){
			$city = $mysqli->real_escape_string((string)$params->city);
		}
		
		if(isset($params->state)){
			$state = $mysqli->real_escape_string((string)$params->state);
		}
		
		if(isset($params->zip)){
			$zip = $mysqli->real_escape_string((string)$params->zip);
		}
		
		if(isset($params->phone)){
			$phone = $mysqli->real_escape_string((string)$params->phone);
		}
		
		if(isset($params->website)){
			$website = $mysqli->real_escape_string((string)$params->website);
		}
		if(isset($params->hours)){
			$hours = $mysqli->real_escape_string((string)$params->hours);
		}
		
		if($address && $city && $state){
			$key = "39ee84f3ae0ca490055ca19becda2846";
			$geocoder = new OpenCage\Geocoder($key);
			$query = $address." ".$city.",".$state;
			$geo_res = $geocoder->geocode($query);
			$lat = $geo_res["results"][0]["geometry"]["lat"];
			$long = $geo_res["results"][0]["geometry"]["lng"];
		};
		$id = (int)$mysqli->real_escape_string($id);
		
		$stmt = $mysqli->prepare("UPDATE reuse_businesses SET reuseName = ?, reuseAddress= ?, reuseCity= ?, reuseState= ?, reuseZip= ?, reusePhone= ?, reuseWeb= ?, reuseHours= ?, reuseLongitude= ?, reuseLatitude= ? WHERE reuseId = ?");
		
		$stmt->bind_param("ssssssssddi", $new_name, $address, $city, $state, $zip, $phone, $website, $hours, $long, $lat, $id);
		$stmt->execute();
		$mysqli->close();
	
	});

	/*Updates the a reuse Category specified by a category id, in the first argument :cat, and changes its name to :new_cat*/
	
	$app->patch('/reuseCategory/:cat/:new_cat', $check_token, function($cat, $new_cat){

		
		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("UPDATE reuse_categories SET categoryName = ? WHERE categoryId = ?");
		$stmt->bind_param("si", $new_cat, $cat);
		$stmt->execute();
		echo $cat, $new_cat;
	});

	/*Updates the a reuse Item specified by an item id, in the first argument :item, requires the following in JSON format:
		itemName - desired item name
		categoryId - desired category id
	*/
	
	$app->patch('/reuseItems/:item', $check_token, function($item) use($app){
		$request = $app->request();
		$body = $request->getBody();
		$params = json_decode($body);

		$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
		$stmt = $mysqli->prepare("UPDATE reuse_items SET itemName = ?, categoryId = ? WHERE itemId = ?");
		if(isset($params->itemName)){	
			$new_item = $mysqli->real_escape_string((string)$params->itemName);
		}
		else{
			$app->response->setStatus(400);
			exit(1);
		}
		if(isset($params->category)){
			$category = (int)$mysqli->real_escape_string((string)$params->category);
		}
		
		
		$item = (int)$mysqli->real_escape_string($item);
		$stmt->bind_param("sii", $new_item, $category, $item);
		$stmt->execute();
	});
	
	$app->run();
?>

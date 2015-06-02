#!/usr/bin/env php

<?php

	/*One time script used to add latitude and longitude to all businesses in database*/
	require_once('./src/OpenCage.Geocoder.php');


	$key = "39ee84f3ae0ca490055ca19becda2846";
	$geocoder = new OpenCage\Geocoder($key);

	/*Add longitude and latitude to repair businesses*/

	$mysqli = new mysqli("mysql.eecs.oregonstate.edu", "cs419-g4", "RNjFRsBYJK5DVF8d", "cs419-g4");
	$result = $mysqli->query('SELECT repairId, repairName, repairAddress, repairCity, repairState FROM repair_businesses');
	
	while($row = $result->fetch_assoc()){
		$query = $row["repairAddress"]." ".$row["repairCity"].",".$row["repairState"];
		$id = $row["repairId"];
		$geo_res = $geocoder->geocode($query);
		$lat = $geo_res["results"][0]["geometry"]["lat"];
		$lng = $geo_res["results"][0]["geometry"]["lng"];

		$stmt = $mysqli->prepare("UPDATE repair_businesses SET repairLongitude = ?, repairLatitude = ? WHERE repairId  = ?");
		$stmt->bind_param("ddi", $lng, $lat, $id);
		$stmt->execute();
		echo $row["repairName"]." coordinates input\n";
		
		

	};
	$result->free();
	

	/*Add longitude and latitude to reuse businesses*/
	$result = $mysqli->query('SELECT reuseId, reuseName, reuseAddress, reuseCity, reuseState FROM reuse_businesses');

	while($row = $result->fetch_assoc()){
		$query = $row["reuseAddress"]." ".$row["reuseCity"].",".$row["reuseState"];
		$id = $row["reuseId"];
		$geo_res = $geocoder->geocode($query);
		$lat = $geo_res["results"][0]["geometry"]["lat"];
		$lng = $geo_res["results"][0]["geometry"]["lng"];

		$stmt = $mysqli->prepare("UPDATE reuse_businesses SET reuseLongitude = ?, reuseLatitude = ? WHERE reuseId = ?");
		$stmt->bind_param("ddi", $lng, $lat, $id);
		$stmt->execute();

		echo $row["reuseName"]." coordinates input\n";
	
	};
	$result->free();

	

?>


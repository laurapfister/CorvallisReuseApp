<?php

	/*Makes curl call to resource.php to check if token stored in SESSION is valid*/

	function check_token(){
		session_start();
		if(!isset($_SESSION['token'])){
			/*Redirect to login page*/
			header('location: crrlogin.html');
			exit(1);
	
		}
		$access_token = $_SESSION['token'];
		
		/*Curl call to resource.php*/
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://web.engr.oregonstate.edu/~pfisterl/cs419/resource.php',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array("access_token={$access_token}"
			),	
			CURLOPT_RETURNTRANSFER => 1
		));

		$resp = curl_exec($curl);
		$resp = json_decode($resp);
		$error = $resp->{'error'};
		
		if(isset($error)){
			/*Redirect to login page*/
			header('location: crrlogin.html');
			exit(1);
		}
	}

?>

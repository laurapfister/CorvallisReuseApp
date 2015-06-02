<?php

	/*Login verfication for website*/
	ini_set('display_errors', 1);
	session_start();
	if(isset($_POST['user']))
		$user = htmlspecialchars($_POST['user']);

	if(isset($_POST['password']))
		$password = htmlspecialchars($_POST['password']);

	if(isset($user) && isset($password)){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://web.engr.oregonstate.edu/~pfisterl/cs419/token.php',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				grant_type => 'client_credentials'
			),	
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_USERPWD => "{$user}:{$password}"
		));
		$resp = curl_exec($curl);
		$resp = json_decode($resp);
		$access_token = $resp->{'access_token'};
		
	
		$_SESSION['token'] = $access_token;
		curl_close($curl);
	}
	else{
		http_response_code(404);
		exit(1);
	}
	session_close();
	exit();


?>

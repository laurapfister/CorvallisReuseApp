<?php

	/*Login verfication for website*/
	
	
	if(isset($_POST['user']))
		$user = htmlspecialchars($_POST['user']);

	if(isset($_POST['password']))
		$password = htmlspecialchars($_POST['password']);

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

	$resp = json_decode(curl_exec($curl));
	$access_token = $resp->{'access_token'};

	if(isset($access_token)){
		session_start();
		$_SESSION['token'] = $access_token;
		curl_close($curl);
		exit();
	}
	else{
		curl_close($curl);
		exit(1);
	}
	
	

?>

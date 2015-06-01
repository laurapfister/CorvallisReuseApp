<?php


	function check_token(){
		session_start();
		
		if(isset($_SESSION['token'])){
			$access_token = $_SESSION['token'];
		}
		else{
			header("Location: login.html");
			exit(1);
		}
		

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://web.engr.oregonstate.edu/~pfisterl/cs419/resource.php',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				access_token => $access_token
			),	
			CURLOPT_RETURNTRANSFER => 1
		));

		$resp = json_decode(curl_exec($curl));
		
		return $resp->{'success'};
	}

?>

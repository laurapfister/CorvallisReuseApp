<?php

	/*Resource controller for Oauth2.0 
	  Checks if the token provided is accurate. Effectively provides authentication for given token
	  Resource used: http://bshaffer.github.io/oauth2-server-php-docs/cookbook/*/

	require_once 'server.php';
	
	if(!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){

		$server->getResponse()->send();
		die;
	}
	echo json_encode(array('success' => 'true', 'message' => 'success'));


?>

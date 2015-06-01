<?php

	/*Resource controller for Oauth2.0 
	  Resource used: http://bshaffer.github.io/oauth2-server-php-docs/cookbook/*/

	require_once 'server.php';
	
	if(!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){

		$server->getResponse()->send();
		die;
	}
	echo json_encode(array('success' => true, 'message' => 'you accessed my apis!'));


?>
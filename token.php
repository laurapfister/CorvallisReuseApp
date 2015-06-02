<?php

	/*Token controller for Oauth2.0

	  This takes the provded username and password and checks if it is valid. If it is
          it responds with a temporary token, the user can use to access calls to the database
    	  Resource used: https://bshaffer.github.io/oauth2-server-php-docs/cookbook/*/
	ini_set('display_errors', 1);

	require_once 'oauth2-server-php/src/OAuth2/Autoloader.php';
	OAuth2\Autoloader::register();
	require_once 'server.php';
	
	$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();


?>

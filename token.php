<?php

	/*Token controller for Oauth2.0
    	  Resource used: https://bshaffer.github.io/oauth2-server-php-docs/cookbook/*/
	ini_set('display_errors', 1);

	require_once 'oauth2-server-php/src/OAuth2/Autoloader.php';
	OAuth2\Autoloader::register();
	require_once 'server.php';
	
	$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();


?>

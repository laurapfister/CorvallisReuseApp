<?php 
	/*Create and configure OAuth2 Server.
	  This adds OAuth2 functionality to the mysql database.
	  Creates a Client Credentials grant type
	  Resource: https://bshaffer.github.io/oauth2-server-php-docs/cookbook/*/
	
	$dsn = 'mysql:dbname=cs419-g4;host=mysql.eecs.oregonstate.edu';
	$username = 'cs419-g4';
	$password = 'RNjFRsBYJK5DVF8d';

	require_once 'oauth2-server-php/src/OAuth2/Autoloader.php';
	OAuth2\Autoloader::register();

	/*Connect to database*/
	try{
		$pdo = new PDO($dsn, $username, $password);
	} catch(PDOException $e){
		echo 'Connection failed: '. $e->getMessage();
	}
	
	$storage = new OAuth2\Storage\Pdo($pdo);

	$server = new OAuth2\Server($storage);
	
	$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

	$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));


?>

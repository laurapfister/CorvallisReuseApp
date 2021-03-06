/*CREATE tables for OAUTH2.0. 
  Resource used: https://bshaffer.github.io/oauth2-server-php-docs/cookbook/
*/
/*OAUTH2.0 Clients*/
CREATE TABLE IF NOT EXISTS oauth_clients 
(client_id VARCHAR(80) NOT NULL, 
client_secret VARCHAR(80) NOT NULL, 
redirect_uri VARCHAR(2000) NOT NULL,
grant_types VARCHAR(80), 
scope VARCHAR(100), 
user_id VARCHAR(80), 
CONSTRAINT clients_client_id_pk PRIMARY KEY (client_id));

/*OAUTH2.0 Access tokens*/
CREATE TABLE IF NOT EXISTS oauth_access_tokens (access_token VARCHAR(40) 
NOT NULL, client_id VARCHAR(80) NOT NULL, 
user_id VARCHAR(255), expires TIMESTAMP NOT NULL, 
scope VARCHAR(2000), 
CONSTRAINT access_token_pk PRIMARY KEY (access_token));

/*OAUTH2.0 Authorization codes*/
CREATE TABLE IF NOT EXISTS oauth_authorization_codes 
(authorization_code VARCHAR(40) NOT NULL,
 client_id VARCHAR(80) NOT NULL, user_id VARCHAR(255), 
redirect_uri VARCHAR(2000), 
expires TIMESTAMP NOT NULL, scope VARCHAR(2000), 
CONSTRAINT auth_code_pk PRIMARY KEY (authorization_code));

/*OAUTH2.0 Refresh Tokens*/
CREATE TABLE IF NOT EXISTS oauth_refresh_tokens (refresh_token VARCHAR(40) 
NOT NULL, client_id VARCHAR(80) NOT NULL, 
user_id VARCHAR(255), expires TIMESTAMP NOT NULL, scope VARCHAR(2000), 
CONSTRAINT refresh_token_pk PRIMARY KEY (refresh_token));

/*OAUTH2.0 Users*/
CREATE TABLE IF NOT EXISTS oauth_users 
(username VARCHAR(255) NOT NULL, password VARCHAR(2000), 
first_name VARCHAR(255), last_name VARCHAR(255), 
CONSTRAINT username_pk PRIMARY KEY (username));

CREATE TABLE IF NOT EXISTS oauth_scopes (scope TEXT, is_default BOOLEAN);

CREATE TABLE IF NOT EXISTS oauth_jwt (client_id VARCHAR(80) NOT NULL, 
subject VARCHAR(80), public_key VARCHAR(2000), 
CONSTRAINT jwt_client_id_pk PRIMARY KEY (client_id));

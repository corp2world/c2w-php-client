<?php

/*
* Corp2World.com client library version 1.0
* @link http://www.corp2world.com
* Compatible with PHP version 5.3 and higher
* @Copyright 2013
*/

/*
 * Include client library
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

include 'c2w_client.php';

/*
 * Create service using HTTP (non-encrypted communication, user name and password can be exposed to 'middle man')
 */

/*
$service = new com\c2w\C2W_Service(
		"http://corp2world.com:8090/rest/message/post",  // Service URL
		"testuser", // User name
		"password", // User password 
		NULL, // No certificate required for HTTP 
		TRUE  // Print debug information
		);
*/

/*
 * Create service using HTTPS (encrypted communication to protect user name and password from 'middle man')
 */
$service = new \com\c2w\C2W_Service(
		"https://corp2world.com:9443/rest/message/post",  // Service URL
		"testuser", // User name
		"password", // User password
		getcwd() . "/ca_certificate.pem", // Server certificate file
		TRUE  // Print debug information
);


/*
 * Example 1:
 * Create and send simple message with topic and text
 */ 
$message = new \com\c2w\C2W_Message("My message", "Hello, World! Corp2World.com is my gateway to the world of unlimited communication possibilities!");
$service->send($message);


/*
 * Example 2:
 * Create and send message with additional properties
 */
$properties = array("company"=>"UA Soft Services, LLC", "established in" => 2013, "countries"=>"Ukraine, USA");
$message = new com\c2w\C2W_Message("My message with properties", "Hello, World! Corp2World.com is my gateway to the world of unlimited communication possibilities!");
$message->properties = $properties;
$service->send($message);


/*
 * Example 3:
 * Create and send message with dynamic list of recipients (available for corporate customers only)
 * Use actual emiail address in the code below
 */
$recipients = array(1=>array("address1@mail.com","address2@mail.com"));
$message = new com\c2w\C2W_Message("My message with recipients list", "Hello, World! Corp2World.com is my gateway to the world of unlimited communication possibilities!");
$message->channelRecipients = $recipients;
$service->send($message);


?>
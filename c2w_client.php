<?php 


/**
 * Corp2World.com client library version 1.0 
 * @link http://www.corp2world.com
 * Compatible with PHP version 5.3 and higher
 * @Copyright 2013
 * 
 * PHP client library for Corp2World.com message delivery service.
 * 
 * Library consists of two classes: 
 * - C2W_Message represents 'message' object which is sent to the Corp2World central service. 
 * - C2W_Service implements routines to send message in 'JSON' format to RESTful Corp2World service.
 * 
 * This library provides quick and convinient way to send messages to Corp2World service from PHP scripts, but it is 
 * not absolutely required. Corp2World service uses JSON/RESTful communication protocol so it is easy to use service
 * without using this library.
 * 
 */

/**
 * com.c2w  name space
 */
namespace com\c2w;

/*
 * C2W client library 
 */

/**
 * This class represents 'message' object.
 * When message is created the message time is set into current system time and
 * message 'time-to-live' is set into 0, which means message never expiries.
 *
 */
class C2W_Message {
	
	/**
	 * Message topic
	 */
	public $topic = "";
	
	/**
	 * Message text
	 */
	public $text = "";
	
	/**
	 * Message creation timestamp, by default the current time is used. 
	 */
	public $timestamp;
	
	/**
	 * 'Time-to-live' the time period (starting from message timestamp) during which message is considered to be valid
	 */
	public $ttl = 0;
	
	/**
	 * If message is for 'test' purpose (TRUE) or if actual message (FALSE)
	 */
	public $test = FALSE;
	
	/**
	 * Message additional properties
	 */
	public $properties;
	
	/**
	 * List of dynamic recipients 
	 */
	public $channelRecipients;
	
	/**
	 * Create new message
	 * @param String $_topic message topic
	 * @param String $_text message text
	 */
	function __construct($_topic, $_text) {
		$this->topic = $_topic;
		$this->text = $_text;
		$this->timestamp = time() * 1000;
		$properties = array();
	}

}

/**
 * Class implements routines to send message to the Corp2World.com service
 * 
 */
class C2W_Service {
	
	/*
	 * Service url
	 */
	private $url;
	
	/*
	 * Service user name, required for authentification purposes
	 */
	private $user;
	
	/*
	 * Service user password, required for authentification purposes
	 */
	private $password;
	
	/*
	 * Service certificate file name, required if HTTPS (over SSL) protocol is used.
	 * Usually certificate is provided with the library or can be downloaded from the server. 
	 */
	private $certificate;
	
	/*
	 * Debug mode flag
	 */
	private $debug = FALSE;
	
	/**
	 * Create new C2W Service with the given parameters
	 * @param String $_url full URL for Corp2World.com message service (see service documentation)
	 * @param String $_user user name, unique customer name to access Corp2World.com service
	 * @param String $_password user password, customer password to access Corp2World.com service
	 * @param String $_certificate (optional) Corp2World.com server certificate file name, required if HTTPS protocol is used 
	 * @param Boolean $_debug (optional) TRUE if in debug mode (detailed information will be printed to standard output with 'echo'), FALSE if no debug
	 *        information should be printed
	 */
	function __construct($_url, $_user, $_password, $_certificate=NULL, $_debug=FALSE) {
		$this->url = $_url;
		$this->user = $_user;
		$this->password = $_password;
		$this->certificate = $_certificate;
		$this->debug = $_debug;
	}

	/**
	 * Send message to Corp2World.com service.
	 * @param C2W_Message $message message to send 
	 * @return string the result of the call to the servie
	 */
	public function send($message) {

		/*
		 * Convert message object into JSON string
		 */
		$json_message = json_encode($message);
		
		/*
		 * Print debug info if in 'debug' mode
		 */
		if($this->debug) 
			echo "<br><br>".
				 "Corp2World client debug info:<br>".
				 "Sending message: ".$json_message."<br>".
				 "to service at: ".$this->url."<br>".
				 "as user: ".$this->user.
				 " using server certificate file: ".$this->certificate.
				 "<br>";
		
		
		/*
		 * Initializing curl
		 */ 
		$ch = curl_init( $this->url );
		
		/*
		 * Configuring curl options
		 */ 
		$options = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_USERPWD => $this->user . ":" . $this->password,   
				CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
				CURLOPT_POSTFIELDS => $json_message
		);
		
		/*
		 * Set certificate if required
		 */
		if($this->certificate != NULL) {
			$options[CURLOPT_CAINFO] = ( $this->certificate );
		}
		
		/*
		 * Setting curl options
		 */ 
		curl_setopt_array( $ch, $options );
		
		/*
		 * Making call to service
		 */ 
		$result =  curl_exec($ch); 
		
		$error = curl_error ( $ch );
		
		if($error)
			$result = $result." Error: ".$error;
		
		/*
		 * Closing connection
		 */
		curl_close($ch);
		
		/*
		 * Printing debug info if in 'debug' mode
		 */
		if($this->debug)
			echo "Result: ".$result."<br><br>";
		
		/*
		 * Return result
		 */
		return $result;
	}

}

?>
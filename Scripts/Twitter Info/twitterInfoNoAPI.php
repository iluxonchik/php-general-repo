<?php
/****************************************************************************
 *	Retrieve some user info from twitter without using twitter's API.
 *	Please note that many parts of the code are hardcoded and the code itself
	is not very reliable, since a minor change to twitter's html layout might
	break it.
****************************************************************************/

define('TWITTER_URL', 'https://mobile.twitter.com/');
define('TWITTER_200', 'HTTP/1.0 200 OK'); // OK response header

class TwitterInfo {

	public $validUser = true; // track if there was a username error
	
	function __construct($user){
		
		if(isset($user) && !empty($user))
			$this->desktopURL = TWITTER_URL . $user; // generate user URL
		else
			$this->validUser = false; // the argument is empty or nonexistent

		$this->responseCode = get_headers($this->desktopURL)[0]; // get response code from Twitter

		if ($this->responseCode != TWITTER_200)
			// if the responde code is anything but OK, the user is invalid
			$this->validUser = false;

	}

	function getName (){
		// TODO
		return 0;
	}

	function getAbout(){
		// TODO
		return 0;
	}
}

?>
<?php
/****************************************************************************
 *	Retrieve some user info from twitter without using twitter's API.
 *	Please note that many parts of the code are hardcoded and the code itself
	is not very reliable, since a minor change to twitter's html layout might
	break it.
****************************************************************************/

define('TWITTER_URL', 'http://mobile.twitter.com/');
define('TWITTER_ERROR_TEXT', 'Sorry, that page doesn\'t exist'); // error message in case the user is not found

class TwitterInfo {

	public $userError = false; // track if there was a username error
	
	function __construct($user){

		if(isset($user) && !empty($user))
			$this->url = TWITTER_URL . $user; 
		else
			$this->userError = true; // the argument is empty or nonexistent

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
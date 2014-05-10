<?php
/****************************************************************************
 *	Retrieve some user info from twitter without using twitter's API.
 *	Please note that many parts of the code are hardcoded and the code itself
	is not very reliable, since a minor change to twitter's html layout might
	break it.
****************************************************************************/

define('TWITTER_URL', 'https://mobile.twitter.com/');
define('TWITTER_200', 'HTTP/1.0 200 OK'); // OK response header
define('MAX_STRING_LENGTH', 200);

class TwitterInfo {

	public $validUser = true; // track if there was a username error
	
	function __construct($user){
		
		if(isset($user) && !empty($user)){
			$this->user = $user;
			$this->mobileURL = TWITTER_URL . $this->user; // generate user URL
		}
		else
			$this->validUser = false; // the argument is empty or nonexistent

		$this->responseCode = get_headers($this->mobileURL)[0]; // get response code from Twitter

		if (($this->responseCode != TWITTER_200) && (!strpos($this->user, '/')))
			// if the responde code is anything but OK, or the user field contains '/' the user is invalid
			$this->validUser = false;

	}

	function getName(){
		// get twitter name from twitter url
		// TODO: this only works for non-verified profiles
		$urlCont = file_get_contents($this->mobileURL);
		$position = strpos($urlCont, "<div class=\"fullname\">");
		$subStr = substr($urlCont, $position, MAX_STRING_LENGTH);
		$subStr = preg_replace( "/\r|\n/", "", $subStr); // remove new line chars (for preg_match)
		preg_match("/<div class=\"fullname\">(.*?)<\/div>/", $subStr , $match);
		$this->name = trim($match[1]); // remove spaces
		return $this->name;
	}

	function getAbout(){
		// TODO
		return 0;
	}
}

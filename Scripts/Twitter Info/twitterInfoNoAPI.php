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
define('VERIFIED_USER_REGEX', "/<div class=\"fullname\">(.*?)<a/");
define('NON_VERIFIED_USER_REGEX', "/<div class=\"fullname\">(.*?)<\/div>/");
define('USER_POSITION', "<div class=\"fullname\">");
define('ABOUT_POSITION', "<div class=\"dir-ltr\" dir=\"ltr\">");
define('ABOUT_REGEX', "/<div class=\"dir-ltr\" dir=\"ltr\">(.*?)<\/div>/");
define('ERROR_INVALID_USER', -1);

class TwitterInfo {

	public $validUser = true; // track if there was a username error
	
	function __construct($user){
		
		if(isset($user) && !empty($user)){
			$this->user = $user;
			$this->mobileURL = TWITTER_URL . $this->user; // generate user URL
			$this->urlCont = file_get_contents($this->mobileURL);
		}
		else
			$this->validUser = false; // the argument is empty or nonexistent

		$this->responseCode = get_headers($this->mobileURL)[0]; // get response code from Twitter

		if (($this->responseCode != TWITTER_200) && (!strpos($this->user, '/')))
			// if the responde code is anything but OK, or the user field contains '/' the user is invalid
			$this->validUser = false;

	}

	function getName(){
		if ($this->validUser){
			// get twitter name from twitter url
			$position = strpos($this->urlCont, USER_POSITION);
			$subStr = substr($this->urlCont, $position, MAX_STRING_LENGTH);
			$subStr = preg_replace( "/\r|\n/", "", $subStr); // remove new line chars (for preg_match)
			preg_match(NON_VERIFIED_USER_REGEX, $subStr , $match);
			if (empty($match)){
				// if the $match is empty, try another regex
				// this is a regular expression in case the user is verified
				preg_match(VERIFIED_USER_REGEX, $subStr , $match);
			}
			$this->name = trim($match[1]); // remove spaces
			return $this->name;
		}
		else
			return ERROR_INVALID_USER; // the user is not valud
	}

	function getAbout(){
		if($this->validUser){
			$position = strpos($this->urlCont, ABOUT_POSITION);
			$subStr = substr($this->urlCont, $position, MAX_STRING_LENGTH); // get just a part of the urls HTML
			$subStr = preg_replace( "/\r|\n/", "", $subStr); // remove new line chars (for preg_match)
			preg_match(ABOUT_REGEX, $subStr , $match);
			$this->about = trim($match[1]); // remove spaces from the end and the beginning
			return $this->about;
		}
		else
			return ERROR_INVALID_USER; // the user is not valid
	}
}

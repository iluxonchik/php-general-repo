<?php
/*****************************************************************************
 *	Retrieve some user info from twitter without using twitter's API.
 *	Please note that many parts of the code are hardcoded and the code itself
	is not very reliable, since a minor change to twitter's mobile HTML layout 
	might break it.
*	Some parts of the code are repeated and can be separated into even more
	functions.
*****************************************************************************/
require_once('classConstants.php');

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
		/* gets the name specified on user's twitter profile */
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
		/* gets the profile description specified by the user on their profile*/
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

	function getLocation(){
		/* gets the location specified on user's twitter profile */
		if($this->validUser){
			$position = strpos($this->urlCont, LOCATION_POSITION);
			$subStr = substr($this->urlCont, $position, MAX_STRING_LENGTH);
			$subStr = preg_replace( "/\r|\n/", "", $subStr); // remove new line chars (for preg_match)
			preg_match(LOCATION_REGEX, $subStr, $match);
			$this->location = trim($match[1]); // remove spaces from the end and the beginning
			return $this->location;
		}
		else
			return ERROR_INVALID_USER; // the user is not valid
	}

	function getNumbers($positionText){
		/* used as auxilary in getNumTweets(), getNumFollowing() and getNumFollwers()  */
		$position = strpos($this->urlCont, $positionText);
		$subStr = subStr($this->urlCont, $position - 100, MAX_STRING_LENGTH); // the number of followers comes before the NUM_TWEETS_POSITION in twitter's HTML
		$subStr = preg_replace( "/\r|\n/", "", $subStr); // remove new line chars (for preg_match)
		preg_match(NUMBERS_REGEX, $subStr, $match);
		return trim($match[1]); // remove the spaces and return

	}

	function getNumTweets(){
	/* gets the user's number of tweets */
	if ($this->validUser){
			$this->numTweets = $this->getNumbers(NUM_TWEETS_POSITION);
			return $this->numTweets;
		}
		else
			return ERROR_INVALID_USER; // the user is not valid
	}

	function getNumFollowing(){
		/* gets the number of other users the user is following */
		if ($this->validUser){
			$this->numFollowing = $this->getNumbers(NUM_FOLLOWING_POSITION);
			return $this->numFollowing;
		}
		else
			return ERROR_INVALID_USER; // the user is not valid
	}

	function getNumFollowers(){
		/* gets the user's number of follwers */
		if ($this->validUser){
			$this->numFollowers = $this->getNumbers(NUM_FOLLOWERS_POSITION);
			return $this->numFollowers;
		}
		else
			return ERROR_INVALID_USER; // the user is not valid
	}
	
}
<?php
/* Below Are The Constants Used By The TwitterInfo() Class  */
define('TWITTER_URL', 'https://mobile.twitter.com/');
define('TWITTER_200', 'HTTP/1.0 200 OK'); // OK response header
define('MAX_STRING_LENGTH', 200);
define('VERIFIED_USER_REGEX', "/<div class=\"fullname\">(.*?)<a/");
define('NON_VERIFIED_USER_REGEX', "/<div class=\"fullname\">(.*?)<\/div>/");
define('USER_POSITION', "<div class=\"fullname\">");
define('ABOUT_POSITION', "<div class=\"dir-ltr\" dir=\"ltr\">");
define('ABOUT_REGEX', "/<div class=\"dir-ltr\" dir=\"ltr\">(.*?)<\/div>/");
define('LOCATION_POSITION', "<div class=\"location\">");
define('LOCATION_REGEX', "/<div class=\"location\">(.*?)<\/div>/");
define('NUM_TWEETS_POSITION', "<div class=\"statlabel\">Tweets</div>");
define('NUMBERS_REGEX', "/<div class=\"statnum\">(.*?)<\/div>/");
define('NUM_FOLLOWING_POSITION', "<div class=\"statlabel\">Following</div>");
define('NUM_FOLLOWERS_POSITION', "<div class=\"statlabel\">Followers</div>");
define('ERROR_INVALID_USER', -1);
?>
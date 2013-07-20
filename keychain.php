<?php

/* Import using: include($_SERVER['DOCUMENT_ROOT']."/keychain.php"); */

class keychain  
{  
    public function getFoursquareToken()  
    {  
        $token = "KFHT0LQTJQU1W41QSMC3QBUZGDGRYWMSFE5PIJSK14K35R4W&v=20130718";
	return $token;  
    }  
  
    public function getInstagramCredentials()  
    {  
        $credentials = array(
		"id" => "cd5ca076bfd6494ba94756a919325d34",
		"secret" => "93b2abd197d244e2b5adaab20263d0aa",
		"url" => "http://instagram.jonathanlking.com",
		"uri" => "http://instagram.jonathanlking.com/services/oauth"
	);

	return $credentials;
    }  
}  

function foursquare()
{
  $token = "KFHT0LQTJQU1W41QSMC3QBUZGDGRYWMSFE5PIJSK14K35R4W&v=20130718";
	return $token;

}

function instagram()
{

	$credentials = array(
		"id" => "cd5ca076bfd6494ba94756a919325d34",
		"secret" => "93b2abd197d244e2b5adaab20263d0aa",
		"url" => "http://instagram.jonathanlking.com",
		"uri" => "http://instagram.jonathanlking.com/services/oauth"
	);

	return $credentials;
}

?>

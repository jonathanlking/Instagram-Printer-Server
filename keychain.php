<?php

/* Import using: include_once($_SERVER['DOCUMENT_ROOT']."/keychain.php"); */

class keychain
{

	function __construct()
	{
		include_once $_SERVER['DOCUMENT_ROOT']."/database.class.php";
	}


	public function getFoursquareToken()
	{

		$manager = new DatabaseManager;
		return $manager->settingsValueForKey("foursquare_token");;
	}


	public function getInstagramClientId()
	{

		$manager = new DatabaseManager;
		return $manager->settingsValueForKey("instagram_id");
	}


	public function getInstagramClientSecret()
	{

		$manager = new DatabaseManager;
		return $manager->settingsValueForKey("instagram_secret");
	}


	public function getInstagramWebsiteUrl()
	{

		$manager = new DatabaseManager;
		return $manager->settingsValueForKey("instagram_url");
	}


	public function getInstagramRedirectUri()
	{

		$manager = new DatabaseManager;
		$credentials = $this->getInstagramCredentials();
		return $manager->settingsValueForKey("instagram_uri");
	}


}


?>

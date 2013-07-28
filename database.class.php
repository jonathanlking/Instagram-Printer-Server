<?php

$manger = new DatabaseManager;
/* $manger->createDatabase();  */
/* $printId = $manger->addPrint("1093483", "http://www.jonathanlking.com/largePrint.jpg", "http://www.jonathanlking.com/smallPrint.jpg", "instagr.am/p/j3iusadfb3", "176576454254876", "jonathanlking"); */
/* echo $printId; */
/* $manger->removePrint($printId-2); */
/* echo var_dump($manger->printForPrintId(2)); */
/* $print = $manger->printForPrintId(2); */
/* echo $print["SubscriptionId"]; */
/* $manger->updatePropertyOfPrint("SubscriptionId", "782348723948", 2); */
/* echo var_dump($manger->printsWithValueForProperty("SubscriptionId", "176576454254876")); */
/* $manger->resetDatabase(); */


class DatabaseManager
{

	private $databaseName = "database.db";

	function __construct()
	{
	}


	function __destruct()
	{
	}


	public function createDatabase()
	{

		# SQLite tables

		$settings = "CREATE TABLE Settings(
					key 	TEXT	NOT NULL,
					value 	TEXT
					);";

		$print = "CREATE TABLE Print(
					PrintId 		INTEGER 	PRIMARY KEY,
					SubscriptionId 	INTEGER 	NOT NULL,
					DateTaken		TIMESTAMP	NOT NULL,
					LargePrint		TEXT		NOT NULL,
					SmallPrint		TEXT		NOT NULL,
					InstagramLink	TEXT		NOT NULL,
					Username		TEXT		NOT NULL
					);";

		$addKeys  = "INSERT INTO Settings VALUES ('foursquare_token', '');";
		$addKeys .= "INSERT INTO Settings VALUES ('instagram_id', '');";
		$addKeys .= "INSERT INTO Settings VALUES ('instagram_secret', '');";
		$addKeys .= "INSERT INTO Settings VALUES ('instagram_url', '');";
		$addKeys .= "INSERT INTO Settings VALUES ('instagram_uri', '');";
		$addKeys .= "INSERT INTO Settings VALUES ('password', '');";
		$addKeys .= "INSERT INTO Settings VALUES ('printing', '');";

		$subscription = "CREATE TABLE Subscription(
					SubscriptionId 			INTEGER 	PRIMARY KEY,
					InstagramSubscription 	INTEGER 	NOT NULL,
					Type					TEXT		NOT NULL,
					Value					TEXT		NOT NULL,
					GalleryTitle			TEXT		NOT NULL,
					LogoFilename			TEXT				,
					Active					BOOLEAN		NOT NULL,
					Printing				BOOLEAN		NOT NULL,
					DisplayGallery			BOOLEAN		NOT NULL
					);";

		$table = "CREATE TABLE PrintQueue(
					PrintId 		INTEGER 	PRIMARY KEY,
					SubscriptionId 	INTEGER 	NOT NULL,
					DateTaken		TIMESTAMP	NOT NULL,
					LargePrint		TEXT		NOT NULL,
					SmallPrint		TEXT		NOT NULL,
					InstagramLink	TEXT		NOT NULL,
					Username		TEXT		NOT NULL
					);";

		$create = $settings.$addKeys.$print.$subscription.$table;

		$database = new SQLiteDatabase($this->databaseName);
		$result = $database->queryExec($create, $error);
		unset($database);

		if (!$result) die("Cannot create database as $error.");
		else echo "Database created successfully";

	}
	
	public function resetDatabase() {
		
		unlink($this->databaseName);
		$this->createDatabase();
	}


	public function addPrint($dateTaken, $largePrintUrl, $smallPrintUrl, $instagramLink, $subscriptionId, $username)
	{

		/* Returns print id */

		$database = new SQLiteDatabase($this->databaseName);
		$command = "INSERT INTO Print (SubscriptionId, DateTaken, LargePrint, SmallPrint, InstagramLink, Username) VALUES('$subscriptionId', '$dateTaken', '$largePrintUrl', '$smallPrintUrl', '$instagramLink', '$username')";
		$result = $database->query($command);
		$printId = $database->lastInsertRowID();
		unset($database);

		return $printId;
	}


	public function removePrint($printId)
	{

		$database = new SQLiteDatabase($this->databaseName);
		$command = "DELETE FROM Print WHERE PrintId = $printId";
		$result = $database->query($command);
		unset($database);

		return $result;
	}


	public function printForPrintId($printId)
	{

		/* Returns print object */

		$database = new SQLiteDatabase($this->databaseName);
		$query = $database->query("SELECT * FROM Print WHERE PrintId = $printId");
		$print = $query->fetch();
		unset($database);

		return $print;
	}


	public function updatePropertyOfPrint($property, $value, $printId)
	{

		/* Returns bool success */

		$database = new SQLiteDatabase($this->databaseName);
		$command = "UPDATE Print SET $property = $value WHERE PrintId = $printId";
		$result = $database->query($command);
		unset($database);

		return $result;
	}




	public function printsWithValueForProperty($property, $value)
	{

		/* Returns array of prints */

		$database = new SQLiteDatabase($this->databaseName);
		$query = $database->arrayQuery("SELECT * FROM Print WHERE $property = $value");
		$prints = new ArrayObject;
		foreach ($query as $row) $prints->append($row);
		unset($database);

		return $prints;

	}


	public function latestPrintForSubscription($subscriptionId)
		{ /* Returns print id */}


	public function addSubscription($dateTaken, $largePrintUrl, $smallPrintUrl, $instagramLink, $subscriptionId)
		{ /* Returns print id */}


}


?>
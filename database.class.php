<?php

$manger = new DatabaseManager;
/* $manger->createDatabase();  */
/* $printId = $manger->addPrint("1093483", "http://www.jonathanlking.com/largePrint.jpg", "http://www.jonathanlking.com/smallPrint.jpg", "instagr.am/p/j3iusadfb3", "176576454254876", "jonathanlking"); */
/* echo $printId; */
/* $manger->removePrint($printId-2); */
/* echo var_dump($manger->printForPrintId(2)); */
/* $print = $manger->printForPrintId(2); */
echo $print["SubscriptionId"];
/* $manger->updatePropertyOfPrint("SubscriptionId", "782348723948", 2); */
$manger->printsWithValueForProperty("SubscriptionId", "176576454254876");


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

		$handle = sqlite_open($this->databaseName, 0666, $error);
		if (!$handle) die ($error);

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
		$success = sqlite_exec($handle, $create, $error);

		if (!$success) die("<p>Cannot create database. $error.</p>");
		else echo "<p>Database created successfully</p>";

		sqlite_close($handle);

	}


	public function addPrint($dateTaken, $largePrintUrl, $smallPrintUrl, $instagramLink, $subscriptionId, $username)
	{

		/* Returns print id */
		$handle = sqlite_open($this->databaseName, 0666, $error);
		if (!$handle) die ($error);
		$command = "INSERT INTO Print (SubscriptionId, DateTaken, LargePrint, SmallPrint, InstagramLink, Username) VALUES('$subscriptionId', '$dateTaken', '$largePrintUrl', '$smallPrintUrl', '$instagramLink', '$username')";
		$success = sqlite_exec($handle, $command, $error);
		if (!$success) die("Cannot add print. $error.");
		$printId = sqlite_last_insert_rowid($handle);
		sqlite_close($handle);

		return $printId;
	}


	public function removePrint($printId)
	{

		/* Returns bool success */
		$handle = sqlite_open($this->databaseName, 0666, $error);
		if (!$handle) die ($error);
		$command = "DELETE FROM Print WHERE PrintId = $printId";
		$success = sqlite_exec($handle, $command, $error);
		sqlite_close($handle);

		return $success;

	}


	public function printForPrintId($printId)
	{

		/* Returns print object */
		$handle = sqlite_open($this->databaseName, 0666, $error);
		if (!$handle) die ($error);
		$command = "SELECT * FROM Print WHERE PrintId = $printId";
		$query = sqlite_query($handle, $command);
		$results = sqlite_fetch_array($query, SQLITE_ASSOC);
		sqlite_close($handle);

		return $results;

	}


	public function updatePropertyOfPrint($property, $value, $printId)
	{

		/* Returns bool success */

		$handle = sqlite_open($this->databaseName, 0666, $error);
		if (!$handle) die ($error);
		$command = "UPDATE Print SET $property = $value WHERE PrintId = $printId";
		$success = sqlite_exec($handle, $command, $error);
		sqlite_close($handle);

		return $success;

	}




	public function printsWithValueForProperty($property, $value)
	{

		/* Returns array of prints */

		$database = new SQLiteDatabase($this->databaseName);
		$query = $database->arrayQuery("SELECT * FROM Print WHERE $property = $value");
		
		foreach ($query as $row) {

			echo var_dump($row);
		}

		unset($database);
		/* 		return $results; */

	}


	public function latestPrintForSubscription($subscriptionId)
		{ /* Returns print id */}


	public function addSubscription($dateTaken, $largePrintUrl, $smallPrintUrl, $instagramLink, $subscriptionId)
		{ /* Returns print id */}


}


?>
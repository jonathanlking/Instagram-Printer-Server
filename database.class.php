<?php

$manager = new DatabaseManager;
/* $manager->createDatabase();  */
/* $printId = $manager->addPrint("1093483", "http://www.jonathanlking.com/largePrint.jpg", "http://www.jonathanlking.com/smallPrint.jpg", "instagr.am/p/j3iusadfb3", "176576454254876", "jonathanlking"); */
/* echo $printId; */
/* $manager->removePrint($printId-2); */
/* echo var_dump($manager->printForPrintId(2)); */
/* $print = $manager->printForPrintId(2); */
/* echo $print["SubscriptionId"]; */
/* $manager->updatePropertyOfPrint("SubscriptionId", "782348723948", 2); */
/* echo var_dump($manager->printsWithValueForProperty("SubscriptionId", "176576454254876")); */
/* $manager->resetDatabase(); */
/* echo $manager->settingsValueForKey("printing"); */
/* $manager->settingsSetValueForKey("printing", "48"); */
// Make sure you pass in a string - not an int
/* echo $manager->latestPrintForSubscription("176576454254876"); */
/* echo var_dump($manager->printsWithValueForProperty("SubscriptionId", "176576454254876")); */

/* echo $manager->addSubscription("108342834", "tag", "testprint", "#testprint", "", "1", "0", "1"); */
/* $manager->removeSubscription(2); */
/* echo var_dump($manager->activeSubscription()); */
/* echo var_dump($manager->subscriptionsWithValueForProperty("type", "tag")); */

/* $manager->addPrintToQueue(2); */
/* echo var_dump($manager->printAtFrontOfQueue()); */
/* $manager->resetQueue(); */


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
					Key 	TEXT	NOT NULL,
					Value 	TEXT
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


	public function resetDatabase()
	{

		unlink($this->databaseName);
		$this->createDatabase();
	}


	# Print Functions

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
		$command = "DELETE FROM Print WHERE PrintId = '$printId'";
		$result = $database->query($command);
		unset($database);

		return $result;
	}


	public function printForPrintId($printId)
	{

		/* Returns print object */

		$database = new SQLiteDatabase($this->databaseName);
		$query = $database->query("SELECT * FROM Print WHERE PrintId = '$printId'");
		$print = $query->fetch();
		unset($database);

		return $print;
	}


	public function updatePropertyOfPrint($property, $value, $printId)
	{

		/* Returns bool success */

		$database = new SQLiteDatabase($this->databaseName);
		$command = "UPDATE Print SET $property = '$value' WHERE PrintId = '$printId'";
		$result = $database->query($command);
		unset($database);

		return $result;
	}


	public function printsWithValueForProperty($property, $value)
	{

		/* Returns array of prints */

		$database = new SQLiteDatabase($this->databaseName);
		$query = $database->arrayQuery("SELECT * FROM Print WHERE $property = '$value'");
		$prints = new ArrayObject;
		foreach ($query as $row) $prints->append($row);
		unset($database);

		return $prints;

	}


	public function latestPrintForSubscription($subscriptionId)
	{

		/* Returns print id */

		$database = new SQLiteDatabase($this->databaseName);
		$query = $database->query("SELECT * FROM Print WHERE SubscriptionId = '$subscriptionId' ORDER BY PrintId DESC");
		$print = $query->fetch();
		unset($database);

		return $print;
	}


	# Settings Functions

	function settingsSetValueForKey($key, $value)
	{

		/* Returns bool success */

		$database = new SQLiteDatabase($this->databaseName);
		$command = "UPDATE Settings SET Value = '$value' WHERE Key = '$key'";
		$result = $database->query($command, $error);
		echo $error;
		unset($database);

		return $result;
	}


	function settingsValueForKey($key)
	{

		/* Returns value for key */

		$database = new SQLiteDatabase($this->databaseName);
		$query = $database->query("SELECT * FROM Settings WHERE Key = '$key'");
		$row = $query->fetch();
		unset($database);

		return $row["Value"];
	}


	# Subscription Functions

	public function addSubscription($instagramSubscription, $type, $value, $galleryTitle, $logoFilename, $active, $printing, $displayGallery)
	{

		/* Returns subscription id */

		$database = new SQLiteDatabase($this->databaseName);
		$command = "INSERT INTO Subscription (InstagramSubscription, Type, Value, GalleryTitle, LogoFilename, Active, Printing, DisplayGallery) VALUES('$instagramSubscription', '$type', '$value', '$galleryTitle', '$logoFilename', '$active', '$printing', '$displayGallery')";
		$result = $database->query($command);
		$subscriptionId = $database->lastInsertRowID();
		unset($database);

		return $subscriptionId;
	}


	public function removeSubscription($subscriptionId)
	{

		/* Returns bool success */

		$database = new SQLiteDatabase($this->databaseName);
		$command = "DELETE FROM Subscription WHERE SubscriptionId = '$subscriptionId'";
		$result = $database->query($command);
		unset($database);

		return $result;
	}


	public function updatePropertyOfSubscription($property, $value, $subscriptionId)
	{

		/* Returns bool success */

		$database = new SQLiteDatabase($this->databaseName);
		$command = "UPDATE Subscription SET $property = '$value' WHERE SubscriptionId = '$subscriptionId'";
		$result = $database->query($command);
		unset($database);

		return $result;
	}


	public function subscriptionsWithValueForProperty($property, $value)
	{

		/* Returns array of subscriptions */

		$database = new SQLiteDatabase($this->databaseName);
		$query = $database->arrayQuery("SELECT * FROM Subscription WHERE $property = '$value'");
		$subscriptions = new ArrayObject;
		foreach ($query as $row) $subscriptions->append($row);
		unset($database);

		return $subscriptions;

	}


	public function activeSubscription()
	{

		/* Returns active subscription */

		$database = new SQLiteDatabase($this->databaseName);
		$query = $database->query("SELECT * FROM Subscription WHERE Active = '1' ORDER BY SubscriptionId DESC");
		$subscription = $query->fetch();
		unset($database);

		return $subscription;
	}


	# Print Queue Functions

	public function addPrintToQueue($printId)
	{

		/* Returns bool success */

		$database = new SQLiteDatabase($this->databaseName);
		$command = "INSERT INTO PrintQueue SELECT * FROM Print WHERE PrintId = '$printId'";
		$result = $database->query($command);
		unset($database);

		return $result;
	}


	public function removePrintFromQueue($printId)
	{

		/* Returns bool success */

		$database = new SQLiteDatabase($this->databaseName);
		$command = "DELETE FROM PrintQueue WHERE PrintId = '$printId'";
		$result = $database->query($command);
		unset($database);

		return $result;
	}


	public function printAtFrontOfQueue()
	{

		/* Returns the print at the front og the print queue */

		$database = new SQLiteDatabase($this->databaseName);
		$query = $database->query("SELECT * FROM PrintQueue ORDER BY PrintId ASC");
		$print = $query->fetch();
		unset($database);

		return $print;

	}


	public function resetQueue()
	{

		/* Returns bool success */

		$database = new SQLiteDatabase($this->databaseName);
		$command = "DELETE FROM PrintQueue";
		$result = $database->query($command);
		unset($database);

		return $result;

	}


}


?>
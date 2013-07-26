Objects
=======

Print Object:
------------

`PrintId` - Primary key, Int, auto-increment  
`SubscriptionId` - Int, Not Null - The subscription the print was created as a result of. This is the primary key of subscription from the 'Suscription' table.  
`DateTaken` - Timestamp, Not Null, - Unix timestamp of when the photo was taken  
`LargePrint` - Text, Not Null - Url of the 960x640 print, used for printing  
`SmallPrint` - Text, Not Null - Url of the 48x320 print, used as a thumbnail in the gallery  
`InstagramLink` - Text, Not Null - Link to the Instagram photo page  
`Username` - Text, Not Null - The username of the person who took the photo  

Subscription Object:
-------------------

`SubscriptionId` - Primary key, Int, auto-increment  
`InstagramSubscription` - Int, Not Null - The Instagram subscription number  
`Type` - Text, Not Null - The type of subscription: Tag or Location  
`Value` - Text, Not Null - The value of the subscription eg. "sunsets" or "13687" (Big Ben, London)  
`GalleryTitle` - Text, Not Null - The title for the online print gallery  
`LogoFilename` - Text - The file name of the logo you want placed on the bottom of the print. If it is NULL then no logo will be added.  
`Active` - Bool, Not Null - Whether or not the subscription is active or has expired  
`Printing` - Bool, Not Null - Whether or not to add new prints to the print queue to print  
`DisplayGallery` - Bool, Not Null - Whether or not to make the gallery visable online.  

Tables
======

Settings:
--------
`foursquare_token` - Foursquare API token, used for the location search functionality  
`instagram_id` - Instagram client id, used when making Instagram API calls  
`instagram_secret` - Instagram client secret  
`instagram_url` - Instagram callback url, this is the url which will be called by the subscription  
`instagram_uri` - Instagram oAuth redirect url  
`password` - The password set, required to change settings  
`printing` - Whether to print, this pauses/resumes printing and all queued prints will still be printed  

	$table = "CREATE TABLE Settings(
	key 	TEXT	NOT NULL,
	value 	TEXT
	);";

	$add  = "INSERT INTO Settings VALUES ('foursquare_token', '');";
	$add .= "INSERT INTO Settings VALUES ('instagram_id', '');";
	$add .= "INSERT INTO Settings VALUES ('instagram_secret', '');";
	$add .= "INSERT INTO Settings VALUES ('instagram_url', '');";
	$add .= "INSERT INTO Settings VALUES ('instagram_uri', '');";
	$add .= "INSERT INTO Settings VALUES ('password', '');";
	$add .= "INSERT INTO Settings VALUES ('printing', '');";
	
	$setup = sqlite_exec($handle, $add, $error);
	
	// $value is new value to be set and $key is the key for that value
	$edit = "UPDATE Settings SET 'value' = '$value' WHERE key = '$key'";


PrintQueue:
----------
List of Print Objects  

	$table = "CREATE TABLE PrintQueue(
	PrintId 		INTEGER 	PRIMARY KEY,
	SubscriptionId 	INTEGER 	NOT NULL,
	DateTaken		TIMESTAMP	NOT NULL,
	LargePrint		TEXT		NOT NULL,
	SmallPrint		TEXT		NOT NULL,
	InstagramLink	TEXT		NOT NULL,
	Username		TEXT		NOT NULL	
	);";

Subscription:
------------
List of Subscription Objects  

	$table = "CREATE TABLE Subscription(
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


Print:
-----
List of Print Objects  

	$table = "CREATE TABLE Print(
	PrintId 		INTEGER 	PRIMARY KEY,
	SubscriptionId 	INTEGER 	NOT NULL,
	DateTaken		TIMESTAMP	NOT NULL,
	LargePrint		TEXT		NOT NULL,
	SmallPrint		TEXT		NOT NULL,
	InstagramLink	TEXT		NOT NULL,
	Username		TEXT		NOT NULL
	);";

<?php

function createTable() {
  
	$handle = sqlite_open('data.db', 0666, $error);
	if (!$handle) die ($error);
	
	// Create the table 
	$command = "CREATE TABLE Queue(
	id INTEGER PRIMARY KEY,
	URL TEXT NOT NULL
	)";

	$ok = sqlite_exec($handle, $command, $error);

	if (!$ok) die("Cannot create database. $error. </br></br>");
	else echo "Database created successfully</br></br>";
	
	sqlite_close($handle);	
}

function tableExists() {
	
	$handle = sqlite_open('data.db', 0666, $error);
	if (!$handle) die ($error);

	$check = "SELECT name FROM sqlite_master WHERE type='table' AND name='Queue'";
	$responce = sqlite_query($handle, $check);
	$contents = sqlite_fetch_array($responce, SQLITE_ASSOC);

	return $contents;
}

function requestPrintURL() {

	$handle = sqlite_open('data.db', 0666, $error);
	if (!$handle) die ($error);
	
	$query = "SELECT * FROM Queue ORDER BY ROWID ASC LIMIT 1";
	$result = sqlite_query($handle, $query);
	$row = sqlite_fetch_array($result, SQLITE_ASSOC); 

	// If the table contains a row
	if ($row) {
		echo($row['URL']);
		$query = "DELETE FROM Queue WHERE id=".$row['id'];
		$result = sqlite_query($handle, $query);	
	}

/* 	else echo("Nothing to print."); */
	
	sqlite_close($handle);
}

function printDatabase() {
	
	$handle = sqlite_open('data.db', 0666, $error);
	if (!$handle) die ($error);

	//Do the query
	$query = "SELECT * FROM Queue";
	$result = sqlite_query($handle, $query);
	//iterate over all the rows
	while($row = sqlite_fetch_array($result, SQLITE_ASSOC)){
    	//iterate over all the fields
    	foreach($row as $key => $val){
        	//generate output
        	echo $key . ": " . $val . "<BR />";
        }
    }

    sqlite_close($handle);
}

function addPrintURL($printURL) {
	
	$handle = sqlite_open('data.db', 0666, $error);
	if (!$handle) die ($error);
    
	$command = "INSERT INTO Queue VALUES(NULL,'$printURL')";

	$responce = sqlite_exec($handle, $command);
	if (!$responce) die("Cannot add print to queue.");
	else echo "Print added to queue.";

	sqlite_close($handle);
}

function usage() {
	
	echo("Usage:</br></br>Request print URL = ?requestPrintURL</br>List items in database = ?printDatabase</br>Add URL of a photo to print = ?addPrintURL&url=<i>urlOfPhoto</i>"); 
}


if (!tableExists()) createTable();

if(isset($_GET['requestPrintURL'])) die(requestPrintURL());
if(isset($_GET['printDatabase'])) die(printDatabase());
if(isset($_GET['addPrintURL']) && isset($_GET['url'])) die(addPrintURL($_GET['url']));
else usage();

?>

<?php

require_once ("config.php");
require_once ("instagram.class.php");
global $client_id;

function get_data($url) {
  	$ch = curl_init();
  	$timeout = 5;
  	curl_setopt($ch, CURLOPT_URL, $url);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  	$data = curl_exec($ch);
  	curl_close($ch);
  	return $data;
}

function logData($data) {
	
	$ALL = date("F j, Y, g:i:s:u a")." ".$data."\r\n";
	file_put_contents('link.log', $ALL, FILE_APPEND);
}

// Get the media_id from the link
$link = $_REQUEST["link"];
$url = "http://api.instagram.com/oembed?url=$link";
$data = get_data($url);
$data = json_decode($data);
$mediaId = $data->media_id;

logData("$link");

if(empty($mediaId)) {
	
	// The Link provided is probably not real, or instagram are having some real problems...
	// Therefore return here...
	return;		
}

// Get the image details from the media_id

$instagram = new Instagram($client_id);
$media = $instagram->getMedia($mediaId);

$username = $media->data->user->username;
$profilePictureURL = $media->data->user->profile_picture;
$photoURL = $media->data->images->standard_resolution->url;
$creationTime = $media->data->created_time;
$location = $media->data->location->name;
$caption = $media->data->caption->text;
$likes = $media->data->likes->count;
$link =  $media->data->link;

//set POST variables
$url = 'http://print.jonathanlking.com/engine/generate.php';
$fields =  array(
'username' => $username,
'profilePictureURL' => $profilePictureURL,
'photoURL' => $photoURL,
'creationTime' => $creationTime,
'location' => $location,
'caption' => $caption,
'likes' => $likes,
'link' => $link,
'logo' => $logo
);
	
//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
// This returns the data rather than printing it! Very important
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//save to the print directory if necessary
/*
$savePath = 'prints/'.$objectId.'/latest.jpg';
file_put_contents($savePath, $picture);
*/

//execute post

$picture = curl_exec($ch);

if($_REQUEST["format"] === "base64") {
	$encodedPicture = base64_encode($picture);
	echo($encodedPicture);
}

else echo($picture);

?>

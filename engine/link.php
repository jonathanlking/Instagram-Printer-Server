<?php

require_once "generate.class.php";
include $_SERVER['DOCUMENT_ROOT']."/keychain.php";

function instagramMediaIdFromLink($link)
{
	$curl = curl_init();
	curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => "http://api.instagram.com/oembed?url=$link",
		));


	$responce = curl_exec($curl);
	curl_close($curl);

	$data = json_decode($responce);
	return $data->media_id;
}


function instagramPrintFromMediaId($mediaId, $clientId)
{

	$curl = curl_init();
	curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => "https://api.instagram.com/v1/media/$mediaId?client_id=$clientId",
		));


	$responce = curl_exec($curl);
	curl_close($curl);

	$media = json_decode($responce);

	$username = $media->data->user->username;
	$profilePictureURL = $media->data->user->profile_picture;
	$photoURL = $media->data->images->standard_resolution->url;
	$creationTime = $media->data->created_time;
	$location = $media->data->location->name;
	$caption = $media->data->caption->text;
	$likes = $media->data->likes->count;
	$link = $media->data->link;
	$logo = "";

	$printGenerator = new PrintGenerator($username, $location, $caption, $link, $profilePictureURL, $photoURL, $creationTime, $likes, $logo);
	$print = $printGenerator->getPrintJpeg();

	return $print;

}


function instagramPrintFromLink($link, $clientId)
{

	// Get the Instagram media id from the link
	$mediaId = instagramMediaIdFromLink($link);
	if (empty($mediaId)) die("No Media ID!");

	$print = instagramPrintFromMediaId($mediaId, $clientId);
	
	return $print;

}


$keychain = new keychain;
$clientId = $keychain->getInstagramClientId();

$link = $_REQUEST["link"];
if (empty($link)) die("No Link!");

$print = instagramPrintFromLink($link, $clientId);

if ($_REQUEST["format"] === "base64") echo base64_encode($print);
else echo $print;

?>

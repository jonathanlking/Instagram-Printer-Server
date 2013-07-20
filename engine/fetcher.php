<?php

require_once 'instagram.class.php';
include $_SERVER['DOCUMENT_ROOT']."/keychain.php";

$keychain = new keychain;
$clientID = $keychain->getInstagramClientId();

$tag = "sunset";

$url = "https://api.instagram.com/v1/tags/$tag/media/recent?client_id=$clientID";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);

$media = json_decode($data, true)=>data;

foreach ($media as $value) {

	$username = $value['user']['username'];
	$profilePictureURL = $value['user']['profile_picture'];
	$photoURL = $value['images']['standard_resolution']['url'];
	$creationTime = $value['created_time'];
	$location = $value['location']['name'];
	$caption = $value['caption']['text'];
	$caption = $output = preg_replace('/[^(\x20-\x7F)]*/', '', $caption);
	$likes = $value['likes']['count'];
	$link =  $value['link'];

	echo "<br/>";
	echo "<strong>Location:</strong> $location";
	echo "<br/>";
	echo "<strong>Photo URL:</strong> $photoURL";
	echo "<br/>";
	echo "<strong>Username:</strong> $username";
	echo "<br/>";
	echo "<strong>Photo Taken:</strong> $creationTime";
	echo "<br/>";
	echo '<p><img src="'.$profilePictureURL.'" height="'.$size.'" width="'.$size.'" alt="SOME TEXT HERE"></p>';
	echo "<br/>";
	echo "<strong>Caption:</strong> $caption";
	echo "<br/>";
	echo "<strong>Likes:</strong> $likes";
	echo "<br/>";
	echo "<strong>Link:</strong> $link";
	echo "<br/>";
	echo '<p><img src='.$photoURL.' height="'.$size.'" width="'.$size.'" alt="SOME TEXT HERE"></p>';
}

?>

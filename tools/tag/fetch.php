<?php

require_once $_SERVER['DOCUMENT_ROOT']."/keychain.php";

$keychain = new keychain;
$clientID = $keychain->getInstagramClientId();

$tag = $_GET["tag"];

$url = "https://api.instagram.com/v1/tags/$tag/media/recent?client_id=$clientID";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);

$responce = json_decode($data, true);
$media = $responce["data"];

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

	$takenOnDate = date("d/m/y", $creationTime);
	$takenOnTime = date('g:i A', $creationTime);
	if ($likes>0) $formattedNumberOfLikes = ($likes>1) ? " and has <strong>$likes</strong> likes" : " and has <strong>$likes</strong> like";
	$formattedLocation = (empty($location)) ? "" : " in <strong>$location</strong>";
	$summary = "Taken by <strong>$username</strong> on <strong>$takenOnDate</strong> at <strong>$takenOnTime</strong>".$formattedLocation.$formattedNumberOfLikes;
	
	echo "<p class='result'>$summary</p>";
	echo "<p class='result'>$caption</p>";
	echo "<a href='$link' class='result'><strong>$link</strong></a>";
/* 	echo '<p class="result"><img src="'.$profilePictureURL.'" height="'.$size.'" width="'.$size.'" alt="'.$username.'"></p>'; */
	echo '<p><img src='.$photoURL.' height="'.$size.'" width="'.$size.'" alt="'.$caption.'"></p>';
	echo "<hr>";
}

?>


			
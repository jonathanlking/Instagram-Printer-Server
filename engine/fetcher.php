<?php

require_once 'instagram.class.php';
include($_SERVER['DOCUMENT_ROOT']."/kint/Kint.class.php");

    // Initialize class with client_id
    // Register at http://instagram.com/developer/ and replace client_id with your own
    
    // https://api.instagram.com/v1/tags/coffee/media/recent?access_token=1590313.7bd107b.536e707053af492480317d1807a917d4&callback=callbackFunction
    // https://api.instagram.com/v1/media/popular?client_id=7bd107beff1b41ef8a0bd77c24aaec1f
    // https://api.instagram.com/v1/media/popular?access_token=1590313.7bd107b.536e707053af492480317d1807a917d4
    // https://api.instagram.com/v1/tags/whaletrail/media/recent?access_token=1590313.7bd107b.536e707053af492480317d1807a917d4
    
    $token = "1590313.7bd107b.536e707053af492480317d1807a917d4";
/*     $id = "7bd107beff1b41ef8a0bd77c24aaec1f"; */
    $tag = "blipblup";
    
    
    //set POST variables
	$url = "https://api.instagram.com/v1/tags/$tag/media/recent?access_token=$token";

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	// This returns the data rather than printing it! Very important
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	//execute post
	$data = curl_exec($ch);
	$data = json_decode($data, true);
	$media = $data["data"];
	
	foreach ($media as $value) {
	
	$username = $value['user']['username'];
    $profilePictureURL = $value['user']['profile_picture'];
    $photoURL = $value['images']['standard_resolution']['url'];
    $creationTime = $value['created_time'];
    $location = $value['location']['name'];
    $caption = $value['caption']['text'];
    $caption = $output = preg_replace('/[^(\x20-\x7F)]*/','', $caption);
    $likes = $value['likes']['count'];
    $link =  $value['link'];
	
		echo "<br/>";
        echo("<strong>Location:</strong> $location");
        echo "<br/>";
        echo("<strong>Photo URL:</strong> $photoURL");
        echo "<br/>";
        echo("<strong>Username:</strong> $username");
        echo "<br/>";
        echo("<strong>Photo Taken:</strong> $creationTime");
        echo "<br/>";
        echo '<p><img src="'.$profilePictureURL.'" height="'.$size.'" width="'.$size.'" alt="SOME TEXT HERE"></p>';
        echo "<br/>";
        echo("<strong>Caption:</strong> $caption");
        echo "<br/>";
        echo("<strong>Likes:</strong> $likes");
        echo "<br/>";
        echo("<strong>Link:</strong> $link");
        echo "<br/>";
        echo '<p><img src='.$photoURL.' height="'.$size.'" width="'.$size.'" alt="SOME TEXT HERE"></p>';
	}
    
?>
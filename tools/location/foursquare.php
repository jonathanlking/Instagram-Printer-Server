<?php

include($_SERVER['DOCUMENT_ROOT']."/keychain.php");

$latitude = $_GET["lat"];
$longitude = $_GET["lng"];
$token = foursquare();

$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://api.foursquare.com/v2/venues/search?ll=$latitude,$longitude&oauth_token=$token",
));

$responce = curl_exec($curl);
curl_close($curl);

echo $responce;

?>

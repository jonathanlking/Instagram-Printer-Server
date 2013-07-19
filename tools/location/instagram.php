<?php

/*
$url = "https://api.instagram.com/v1/locations/search";

$latitude = $_GET["lat"];
$longitude = $_GET["lng"];
$token = "1590313.22c41e6.ab449188c6c64d068c84904b7709255f";

$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://api.instagram.com/v1/locations/search?lat=$latitude&lng=$longitude&access_token=$token",
));
*/

$foursquareID = $_GET["id"];
$clientID = "cd5ca076bfd6494ba94756a919325d34";

$curl = curl_init();
// Set some options
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://api.instagram.com/v1/locations/search?foursquare_v2_id=$foursquareID&client_id=$clientID",
));


$responce = curl_exec($curl);
curl_close($curl);

echo $responce;

/* echo file_get_contents("sample.txt"); */

?>
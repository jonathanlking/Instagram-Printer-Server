<?php

include($_SERVER['DOCUMENT_ROOT']."/keychain.php");

$foursquareID = $_GET["id"];
$clientID = instagram()["id"];

$curl = curl_init();
// Set some options
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://api.instagram.com/v1/locations/search?foursquare_v2_id=$foursquareID&client_id=$clientID",
));


$responce = curl_exec($curl);
curl_close($curl);

echo $responce;

?>

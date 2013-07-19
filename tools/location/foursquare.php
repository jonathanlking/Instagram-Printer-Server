<?php

$latitude = $_GET["lat"];
$longitude = $_GET["lng"];
$token = "KFHT0LQTJQU1W41QSMC3QBUZGDGRYWMSFE5PIJSK14K35R4W&v=20130718";

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

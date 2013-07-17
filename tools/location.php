<?php


$url = "https://api.instagram.com/v1/locations/search";

$latitude = "51.60071198679392";
$longitude = "-0.1884612590074";
$token = "1590313.f59def8.ac99b6b1177342a0b64fa9ffc5f736dd";

//set POST variables

$fields = array(
'lat' => $latitude,
'lng' => $longitude,
'access_token' => $token
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

//execute post
$data = curl_exec($ch);

var_dump($data);


?>

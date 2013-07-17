<?php

include($_SERVER['DOCUMENT_ROOT']."/kint/Kint.class.php");

$url = "https://api.instagram.com/v1/locations/search";

$latitude = "51.60071198679392";
$longitude = "-0.1884612590074";
$token = "1590313.22c41e6.ab449188c6c64d068c84904b7709255f";

$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => "https://api.instagram.com/v1/locations/search?lat=$latitude&lng=$longitude&access_token=$token",
));

$responce = curl_exec($curl);
curl_close($curl);

$data = json_decode($responce, true);
$code = $data->code;

print($code); 

d($data);

if ($data["meta"]["code"] !== "200") echo "Something went wrong!";
else {
	
	foreach ($data["data"] as $packet) {

		$location = $packet["name"];
		echo "$location </br>";
    
   }
}


?>

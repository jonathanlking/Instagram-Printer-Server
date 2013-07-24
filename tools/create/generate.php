<?php

header('Content-Type: image/jpeg');
require_once $_SERVER['DOCUMENT_ROOT']."/generate.class.php";

$username = $_GET["username"];
$profilePictureURL = $_GET["profilePictureURL"];
$photoURL = $_GET["photoURL"];
$creationTime = $_GET["creationTime"];
$location = $_GET["location"];
$caption = $_GET["caption"];
$link = $_GET["link"];
$likes = $_GET["likes"];
$logo = $_GET["logo"];

$printGenerator = new PrintGenerator($username, $location, $caption, $link, $profilePictureURL, $photoURL, $creationTime, $likes, $logo);
$image = $printGenerator->getPrintJpeg();
echo base64_encode(($image));


?>

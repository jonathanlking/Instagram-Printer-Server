<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Instagram Printer &middot; Settings</title>
    <meta name="description" content="Instagram Printer">
    <meta name="author" content="Jonathan King">
    <link rel="stylesheet" href="../style.css"><!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>
    <form action="settings.php" method="post" id="form">


<?php

require_once $_SERVER['DOCUMENT_ROOT']."/database.class.php";

$manager = new DatabaseManager;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// They have submitted changes to the keychain

	foreach ($_POST as $key => $value) {

		if (!empty($key) && !empty($value)) $manager->settingsSetValueForKey($key, $value);

	}
	
	echo "Changes Saved!";
}

// They are requesting to see the editor webpage

$foursquareToken = $manager->settingsValueForKey("foursquare_token");
$instagramId = $manager->settingsValueForKey("instagram_id");
$instagramSecret = $manager->settingsValueForKey("instagram_secret");
$instagramUrl = $manager->settingsValueForKey("instagram_url");
$instagramUri = $manager->settingsValueForKey("instagram_uri");
$password = $manager->settingsValueForKey("password");
$printing = $manager->settingsValueForKey("printing");
$checked = ($printing == 1) ? "checked='checked'": "";

echo "<input name='foursquare_token' type='text' placeholder='Foursquare Token' value='$foursquareToken'>";
echo "<input name='instagram_id' type='text' placeholder='Instagram Client Id' value='$instagramId'>";
echo "<input name='instagram_secret' type='text' placeholder='Instagram Client Secret' value='$instagramSecret'>";
echo "<input name='instagram_url' type='text' placeholder='Instagram Callback Url' value='$instagramUrl'>";
echo "<input name='instagram_uri' type='text' placeholder='Instagram Oauth Callback' value='$instagramUri'>";
echo "<input name='password' type='text' placeholder='Password' value='$password'>";
echo "<input name='printing' type='checkbox' $checked>";


?>
	<input type="submit">
    </form>
    <hr>

    <div id="content">
<!--         <p class="help">Enter a location to start.</p> -->
    </div><script src="http://code.jquery.com/jquery-latest.min.js">
<!-- </script> <script src="location.js"> -->
</script> <script src="http://malsup.github.io/jquery.blockUI.js">
</script>
</body>
</html>

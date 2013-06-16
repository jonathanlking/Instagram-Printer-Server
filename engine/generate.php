<?php
// Set the content-type
header('Content-Type: image/jpeg');
/* header('Content-Disposition: attachment; filename="photo.jpg"'); */

// Static Links
$clockImagePath = 'images/clock.png';
$locationPinImagePath = 'images/locationPin.png';
$speechBubbleImagePath = 'images/speechBubble.png';
$heartShapeImagePath = 'images/heartShape.png';

// Dynamic Links
/*
$username = 'jonathanlking';
$profilePictureURL = 'http://images.instagram.com/profiles/profile_1590313_75sq_1336459612.jpg';
$photoURL = 'http://distilleryimage4.s3.amazonaws.com/71fb05d01e1511e2b3f322000a1f96e5_7.jpg';
$creationTime = "1240596879";
$location = 'Emirates Stadium';
$caption = 'This is a mad photo!';
$link = 'http://instagr.am/p/RLXzgDBOpk/';
*/

$username = $_REQUEST["username"];
$profilePictureURL = $_REQUEST["profilePictureURL"];
$photoURL = $_REQUEST["photoURL"];
$creationTime = $_REQUEST["creationTime"];
$location = $_REQUEST["location"];
$caption = $_REQUEST["caption"];
$link = $_REQUEST["link"];
$likes = $_REQUEST["likes"];
$logo = $_REQUEST["logo"];

/* Format the creation time */
if (empty($creationTime)) $creationTimeFormatted = "ERR:OR PM";
else $creationTimeFormatted = gmdate('g:i A', $creationTime);

/* Check that the strings aren't too long and clean up input*/
$username = (strlen($username) > 22) ? substr($username,0,20).'...' : $username;
$location = (strlen($location) > 33) ? substr($location,0,30).'...' : $location;
if (empty($username)) $username = 'username';
/* if (empty($likes)) $likes = '0'; */
if (empty($link)) $link = 'Broken Link';

function decode_characters($info) 
{ 
/*
    $info = mb_convert_encoding($info, "HTML-ENTITIES", "UTF-8"); 
    $info = preg_replace('~^(&([a-zA-Z0-9]);)~',htmlentities('${1}'),$info); 
    return($info); 
*/
	// New version - Get rid of any funny characters!
    $info = iconv("UTF-8", "ISO-8859-1", $info);
    return($info); 
}


// Get rid of any special characters that could cause problems
$username = stripslashes($username); 
$location = stripslashes($location); 
$caption = stripslashes($caption);

$username = decode_characters($username); 
$location = decode_characters($location); 
$caption = decode_characters($caption);  

/* Setup the canvas and basic colours */

// Create the image
$canvas = imagecreatetruecolor(640, 960);

// Create some colors
$white = imagecolorallocate($canvas, 255, 255, 255);
$blue = imagecolorallocate($canvas, 46, 94, 134);
$paleBlue = imagecolorallocate($canvas, 83, 152, 209);
$grey = imagecolorallocate($canvas, 150, 150, 150);
$darkGrey = imagecolorallocate($canvas, 70, 70, 70);

// Load the font
$Font = 'fonts/Helvetica.ttf';
$BoldFont = 'fonts/HelveticaBold.ttf';

// Draw the white background of the image
imagefilledrectangle($canvas, 0, 0, 640, 960, $white);

// Draw the profile picture

/* If the links to the photos are incomplete default to local images */
$profilePicture = (empty($profilePictureURL)) ? imagecreatefromjpeg('images/defaultProfilePicture.jpg') : imagecreatefromstring(file_get_contents($profilePictureURL));
imagecopyresized ($canvas, $profilePicture, 30, 50, 0, 0, 50, 50, 150, 150);
imagedestroy($profilePicture);

// Draw the photo

/* If the links to the photos are incomplete default to local images */
$photo = (empty($photoURL)) ? imagecreatefromjpeg('images/defaultPhoto.jpg') : imagecreatefromstring(file_get_contents($photoURL));
imagecopyresized ($canvas, $photo, 30, 110, 0, 0, 580, 580, 612, 612);
imagedestroy($photo);

// If there is a location add it 
if (!empty($location)) {
	
	// Location Pin
	$locationPinImage = imagecreatefrompng($locationPinImagePath);
	imagecopy($canvas, $locationPinImage, 90, 77, 0, 0, 16, 23);
	imagedestroy($locationPinImage);
	// Location
	imagettftext($canvas, 21, 0, 110, 100, $paleBlue, $BoldFont, $location);
	// Username
	imagettftext($canvas, 21, 0, 90, 70, $blue, $BoldFont, $username);
	
	// Creation Time
	$dimensions = imagettfbbox(21, 0, $BoldFont, $creationTimeFormatted);
	$textWidth = abs($dimensions[4] - $dimensions[0]);
	$horizontalPositionOfCreationTimeText = (611 - $textWidth);
	imagettftext($canvas, 21, 0, $horizontalPositionOfCreationTimeText, 70, $grey, $BoldFont, $creationTimeFormatted);

	// Add the clock image
	$clockImage = imagecreatefrompng($clockImagePath);
	imagecopy($canvas, $clockImage, ($horizontalPositionOfCreationTimeText - 25), 50, 0, 0, 18, 18);
	imagedestroy($clockImage);
}

else {
	
	// Username
	imagettftext($canvas, 21, 0, 90, 92, $blue, $BoldFont, $username);
	
	// Creation Time
	$dimensions = imagettfbbox(21, 0, $BoldFont, $creationTimeFormatted);
	$textWidth = abs($dimensions[4] - $dimensions[0]);
	$horizontalPositionOfCreationTimeText = (608 - $textWidth);
	imagettftext($canvas, 21, 0, $horizontalPositionOfCreationTimeText, 90, $grey, $BoldFont, $creationTimeFormatted);

	// Add the clock image
	$clockImage = imagecreatefrompng($clockImagePath);
	imagecopy($canvas, $clockImage, ($horizontalPositionOfCreationTimeText - 25), 72, 0, 0, 18, 18);
	imagedestroy($clockImage);
}

/* Likes */

/* $likes = intval($likes); */

$likes = intval($likes);

if ($likes>0) {
	
	// Add the heart shape image
	$heartShapeImage = imagecreatefrompng($heartShapeImagePath);
	imagecopy($canvas, $heartShapeImage, 30, 708, 0, 0, 24, 24);
	imagedestroy($heartShapeImage);
	
	// Add the number of likes - fix singular/plural issue
	$formattedNumberOfLikes = ($likes>1) ? "$likes likes" : "$likes like";
	imagettftext($canvas, 21, 0, 60, 730, $blue, $BoldFont, $formattedNumberOfLikes);
} 

/* Comments */

if (!empty($caption)) {

	// Move the line lower if there is a 'likes' section above it
	$heightDifferenceDueToLikes = ($likes>0) ? 35 : 0;

	// Add the speech bubble image
	$speechBubbleImage = imagecreatefrompng($speechBubbleImagePath);
	imagecopy($canvas, $speechBubbleImage, 30, (710 + $heightDifferenceDueToLikes), 0, 0, 24, 24);
	imagedestroy($speechBubbleImage);
	
	// Add the comment - Username
	$dimensions = imagettfbbox(21, 0, $BoldFont, $username);
	$textWidth = abs($dimensions[4] - $dimensions[0]);
	imagettftext($canvas, 21, 0, 60, (730 + $heightDifferenceDueToLikes), $blue, $BoldFont, $username);
	
	// Calculate where to append the rest of the comment
	$horizontalPositionOfComment = ($textWidth + 60 + 10);
	$dimensions = imagettfbbox(21, 0, $Font, $caption);
	$farRightXCoordinate = abs($dimensions[4] - $dimensions[0]) + $horizontalPositionOfComment;

	if ($farRightXCoordinate <= (600 - 10)) {
		
		// Comment fits on one line - no worries here!
		
		// Add the rest of the comment
	    imagettftext($canvas, 22, 0, $horizontalPositionOfComment, (730 + $heightDifferenceDueToLikes), $darkGrey, $Font, $caption);
	}
	
	else {
		
		// We are going to have to bodge it about a bit...
		
		// If there are no likes then we have an extra line available
		$maximumNumberOfLinesForComments = ($likes==0) ? "3" : "2";
		$lineCount;
	
		$firstLine;
		$secondeLine;
		
		$words = explode(" ", $caption);
		
		// First line of the comment
		$index = 0;
		$farRightXCoordinate;
		
		do {

			// Add add a word to the first line
			$firstLine .= $words[$index].' ';
			// Increment the index value by 1
			$index ++;
			
			// Calculate the new far right position
			$dimensions = imagettfbbox(21, 0, $Font, $firstLine);
			$farRightXCoordinate = abs($dimensions[4] - $dimensions[0]) + $horizontalPositionOfComment;
		
  		}
  		
  		while ($farRightXCoordinate <= (600 - 10));
  		
  		$firstLine = array_slice($words,0,($index-1));
  		$firstLine = implode(' ', $firstLine);
  		
  		imagettftext($canvas, 22, 0, $horizontalPositionOfComment, (730 + $heightDifferenceDueToLikes), $darkGrey, $Font, $firstLine);
  		
  		// Second line of the comment
  		
  		// Remove the words the were printed on the first line from the the array
  		$words = array_slice($words,($index - 1));
  		
  		// We are now starting line 2
  		$lineCount = 2;
  		
  		for ($i = 0; $i < count($words); $i++) {
	  
	  		// Working out what the string will be next loop
	  		$string = $secondeLine.$words[$i].$words[($i + 1)];
	  		// Working out how big the box will be next loop
	  		$dimensions = imagettfbbox(21, 0, $Font, $string);
	  		// Working out the far right coordinate next loop
	  		$farRightXCoordinate = abs($dimensions[4] - $dimensions[0]) + 60;
	  		// If the string will be too long return
	  		if ($farRightXCoordinate >= (600 - 10)) {
	  			
	  			if ($lineCount==$maximumNumberOfLinesForComments) {
		  			
		  			$secondeLine = substr($secondeLine, 0, -3)."...";
		  			break;
	  			}
	  			
	  			$secondeLine .= PHP_EOL;
	  			$lineCount ++;
	  		}
	  		// Add the word on
	  		$secondeLine .= $words[$i].' ';
	  		
	  		
    	}
    	
    	imagettftext($canvas, 22, 0, 60, (768 + $heightDifferenceDueToLikes), $darkGrey, $Font, $secondeLine);

		
		}
	

}

// Comments placeholder image
/* imagefilledrectangle($canvas, 580, 165, 30, 705, $blue); */

// Link to Instergram
$dimensions = imagettfbbox(18, 0, $BoldFont, $link);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$horizontalPositionOfCreationTimeText = (608 - $textWidth);
imagettftext($canvas, 18, 0, $horizontalPositionOfCreationTimeText, 910, $darkGrey, $BoldFont, $link);

// Taken on...
$takenOnDate = date("d/m/y", $creationTime);
$takenOnDate = "TAKEN ON $takenOnDate";
$dimensions = imagettfbbox(18, 0, $BoldFont, $takenOnDate);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$horizontalPositionOfCreationTimeText = (608 - $textWidth);
imagettftext($canvas, 18, 0, $horizontalPositionOfCreationTimeText, 880, $darkGrey, $BoldFont, $takenOnDate);

// Add the logo to the bottom left
if (!empty($logo)) {
	
	// Make the name all lowercase
	$logo = strtolower($logo);
	
	$logoImagePath = 'logo/'.$logo.'.png';
	
	// Check that the logo exists
	if (file_exists($logoImagePath)) {
		$logoImage = imagecreatefrompng($logoImagePath);
		list($width, $height) = getimagesize($logoImagePath);
		imagecopy($canvas, $logoImage, 30, (910 - $height), 0, 0, $width, $height);
		imagedestroy($logoImage);
	}
}

// Log 
$details = "$username $profilePictureURL $photoURL $creationTime $location $caption $likes $link $logo $logoImagePath";
$ALL = date("F j, Y, g:i a")." ".$details."\r\n";
file_put_contents('activity.log', $ALL, FILE_APPEND);

// Using imagepng() results in clearer text compared with imagejpeg()
imagejpeg($canvas, NULL, 100);
imagedestroy($canvas);
?>
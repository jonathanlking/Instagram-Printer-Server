<?php

class PrintGenerator
{

	private $clockImagePath;
	private $locationPinImagePath;
	private $speechBubbleImagePath;
	private $heartShapeImagePath;
	private $logoImagePath;
	private $defaultProfilePicturePath;
	private $defaultPhotoPath;

	private $Font;
	private $BoldFont;

	private $username = "";
	private $location = "";
	private $caption = "";
	private $link = "";
	private $profilePictureURL;
	private $photoURL;
	private $creationTime;
	private $likes;
	private $logo;

	private $canvas;

	private $white;
	private $blue;
	private $paleBlue;
	private $grey;
	private $darkGrey;

	public function __construct($username, $location, $caption, $link, $profilePictureURL, $photoURL, $creationTime, $likes, $logo)
	{

		if (!empty($username)) $this->username = $username;
		if (!empty($location)) $this->location = $location;
		if (!empty($caption)) $this->caption = $caption;
		if (!empty($link)) $this->link = $link;
		$this->profilePictureURL = $profilePictureURL;
		$this->photoURL = $photoURL;
		$this->creationTime = $creationTime;
		$this->likes = $likes;
		$this->logo = $logo;

		$this->clockImagePath = $_SERVER['DOCUMENT_ROOT'].'/engine/images/clock.png';
		$this->locationPinImagePath = $_SERVER['DOCUMENT_ROOT'].'/engine/images/locationPin.png';
		$this->speechBubbleImagePath = $_SERVER['DOCUMENT_ROOT'].'/engine/images/speechBubble.png';
		$this->heartShapeImagePath = $_SERVER['DOCUMENT_ROOT']."/engine/images/heartShape.png";
		$this->logoImagePath = $_SERVER['DOCUMENT_ROOT'].'logo/'.$logo.'.png';
		$this->defaultProfilePicturePath = $_SERVER['DOCUMENT_ROOT']."/engine/images/defaultProfilePicture.jpg";
		$this->defaultPhotoPath = $_SERVER['DOCUMENT_ROOT']."/engine/images/defaultPhoto.jpg";

		$this->Font = $_SERVER['DOCUMENT_ROOT']."/engine/fonts/Helvetica.ttf";
		$this->BoldFont = $_SERVER['DOCUMENT_ROOT']."/engine/fonts/HelveticaBold.ttf";

		$this->canvas = imagecreatetruecolor(640, 960);

		$this->white = imagecolorallocate($this->canvas, 255, 255, 255);
		$this->blue = imagecolorallocate($this->canvas, 46, 94, 134);
		$this->paleBlue = imagecolorallocate($this->canvas, 83, 152, 209);
		$this->grey = imagecolorallocate($this->canvas, 150, 150, 150);
		$this->darkGrey = imagecolorallocate($this->canvas, 70, 70, 70);

	}

	
		public function getPrintJpeg()
	{
		
		imageantialias($this->canvas, true);
		
		$this->draw();
		// Start a new output buffer
		ob_start();
		imagejpeg($this->canvas, NULL, 100);
		$print = ob_get_contents();
		// Stop this output buffer
		ob_end_clean();

		imagedestroy($this->canvas);

		return $print;

	}


	public function decodeCharacters($string)
	{
		return iconv("UTF-8", "ISO-8859-1", $string);
	}


	public function imageFromUrl($url)
	{
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $url,
				CURLOPT_CONNECTTIMEOUT => 5
			));

		$image = curl_exec($curl);
		curl_close($curl);

		return $image;
	}


	public function formattedCreationTime()
	{

		if (empty($this->creationTime)) $creationTimeFormatted = "ERR:OR PM";
		else  $creationTimeFormatted = gmdate('g:i A', $this->creationTime);
		return $creationTimeFormatted;
	}


	private function formatInput()
	{

		$username = $this->username;
		$location = $this->location;
		$caption = $this->caption;

		/* Check that the strings aren't too long and clean up input*/
		$username = (strlen($username) > 22) ? substr($username, 0, 20).'...' : $username;
		$location = (strlen($location) > 33) ? substr($location, 0, 30).'...' : $location;

		// Get rid of any special characters that could cause problems
		$username = stripslashes($username);
		$location = stripslashes($location);
		$caption = stripslashes($caption);

		$this->username = $this->decodeCharacters($username);
		$this->location = $this->decodeCharacters($location);
		$this->caption = $this->decodeCharacters($caption);

		/* 		echo "input formatted!".$this->username.$this->location.$this->caption; */

	}


	private function draw()
	{

		$clockImagePath = $this->clockImagePath;
		$locationPinImagePath = $this->locationPinImagePath;
		$speechBubbleImagePath = $this->speechBubbleImagePath;
		$heartShapeImagePath = $this->heartShapeImagePath;
		$logoImagePath = $this->logoImagePath;
		$defaultProfilePicturePath = $this->defaultProfilePicturePath;
		$defaultPhotoPath = $this->defaultPhotoPath;

		$Font = $this->Font;
		$BoldFont = $this->BoldFont;

		$username = $this->username;
		$location = $this->location;
		$caption = $this->caption;
		$link = $this->link;
		$profilePictureURL = $this->profilePictureURL;
		$photoURL = $this->photoURL;
		$creationTime = $this->creationTime;
		$likes = $this->likes;
		$logo = $this->logo;

		$canvas = $this->canvas;

		$white = $this->white;
		$blue = $this->blue;
		$paleBlue = $this->paleBlue;
		$grey = $this->grey;
		$darkGrey = $this->darkGrey;

		$this->formatInput();
		
		# ! Draw the white background of the image
		imagefilledrectangle($canvas, 0, 0, 640, 960, $white);

		// Draw the profile picture

		// If the links to the photos are incomplete default to local images
		$profilePicture = (empty($profilePictureURL)) ? imagecreatefromjpeg($defaultProfilePicturePath) : imagecreatefromstring($this->imageFromUrl($profilePictureURL));
		imagecopyresized($canvas, $profilePicture, 30, 50, 0, 0, 50, 50, 150, 150);
		imagedestroy($profilePicture);

		# ! Draw the photo

		// If the links to the photos are incomplete default to local images
		$photo = (empty($photoURL)) ? imagecreatefromjpeg($defaultPhotoPath) : imagecreatefromstring(($this->imageFromUrl($photoURL)));
		imagecopyresized($canvas, $photo, 30, 110, 0, 0, 580, 580, 612, 612);
		imagedestroy($photo);

		// If there is a location add it

		$creationTimeFormatted = $this->formattedCreationTime();

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

		# ! Likes

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

		# ! Comments

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

				$firstLine = array_slice($words, 0, ($index-1));
				$firstLine = implode(' ', $firstLine);

				imagettftext($canvas, 22, 0, $horizontalPositionOfComment, (730 + $heightDifferenceDueToLikes), $darkGrey, $Font, $firstLine);

				// Second line of the comment

				// Remove the words the were printed on the first line from the the array
				$words = array_slice($words, ($index - 1));

				// We are now starting the second line
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
					// Append the word to the line
					$secondeLine .= $words[$i].' ';


				}

				imagettftext($canvas, 22, 0, 60, (768 + $heightDifferenceDueToLikes), $darkGrey, $Font, $secondeLine);


			}


		}


		# ! Link to Instergram
		$dimensions = imagettfbbox(18, 0, $BoldFont, $link);
		$textWidth = abs($dimensions[4] - $dimensions[0]);
		$horizontalPositionOfCreationTimeText = (608 - $textWidth);
		imagettftext($canvas, 18, 0, $horizontalPositionOfCreationTimeText, 910, $darkGrey, $BoldFont, $link);

		# ! Taken on...

		if (!empty($creationTime)) {

			$takenOnDate = date("d/m/y", $creationTime);
			$takenOnDate = "TAKEN ON $takenOnDate";
			$dimensions = imagettfbbox(18, 0, $BoldFont, $takenOnDate);
			$textWidth = abs($dimensions[4] - $dimensions[0]);
			$horizontalPositionOfCreationTimeText = (608 - $textWidth);
			imagettftext($canvas, 18, 0, $horizontalPositionOfCreationTimeText, 880, $darkGrey, $BoldFont, $takenOnDate);
		}


		# ! Add the logo
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

	}


}


?>
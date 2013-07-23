<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Instagram Printer &middot; Tools</title>
    <meta name="description" content="Instagram Printer">
    <meta name="author" content="Jonathan King">
    <link rel="stylesheet" href="style.css"><!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>
<?php

$directories = array_filter(glob('*'), 'is_dir');
foreach ($directories as $folder) 
{
	$link = "http://".$_SERVER['SERVER_NAME']."/tools/$folder";
	echo "<p><a class='link' href='$link'>$folder</a></p>";
}

?>

</script>
</body>
</html>

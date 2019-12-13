<?php


if (isset($_FILES['newFile']))
{
	//system("mv " . $_FILES['newFile']['tmp_name'] . " /tmp/uploads/");
	echo "good";
	if (move_uploaded_file($_FILES['newFile']['tmp_name'], '/tmp/' . $_FILES['newFile']['name']))
		echo "good too";
	else
	{
		echo "bad too" . PHP_EOL . $_FILES['newFile']['tmp_name'] .PHP_EOL;
	}
}
else
	echo "bad";




?>




<!DOCTYPE html>
<html>
<body>
<form action="muahaha.php" method="post" enctype="multipart/form-data">
	Select file to upload:
	<input type="file" name="newFile" id="newFile"/>
	<input type="submit" value="Upload" name="submit"/>
</form>
	<a href='html.zip'>dotZip</a>
</body>
</html>

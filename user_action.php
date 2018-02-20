<?php
session_start();

include_once "itmanage.php";
include_once "generic.php";
include_once "userfunc.php";

if (!isset($_SESSION['user']) OR $_SESSION['usrlvl']<2)
{
	header("location: index.php");
	die();
	//I don't know if there's a way for it to go through the header
	//you never know
}

//contains every filename/path to IT
$itList = loadIT();

//ordered by batch
$orderedIT = sortIT($itList);



if (isset($_POST['actionChoice']))
{
	switch ($_POST['actionChoice'])
	{
			//move the ITs to the archive folder
		case 'archive':
		archive(getITarray($orderedIT));
	break;
			//create new file only (won't work if the file exists already)
		case 'upload':
		$error;
		checkUpload($error, false); //could use the return value
		$_SESSION['error'] = $error; //will be unset in header_generic
	break;
			//override old file only (won't work if the file doesn't exist)
		case 'update':
		$error;
		checkUpload($error, true); //could use the return value
		$_SESSION['error'] = $error; //will be unset in header_generic
	break;
			//move the raws to trashes folder, pdf deleted
		case 'delete':
		deleteIT(getITarray($orderedIT));
	break;

		default:
	break;
	}
}

foreach ($_POST as $elem)
{
	unset ($elem);
}

foreach ($_FILES as $elem)
{
	unset ($elem);
}


header ("location: index.php");

?>

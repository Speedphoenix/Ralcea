<?php

include_once "config.php";

include_once "functions.php";
include_once "langfunc.php";

//If the user hasn't chosen anything to display we go back to main menu
if (!isset($_GET['choix']) OR $_GET['choix']=='0')
{
	header('Location: index.php');
}

$detailp = true;

$filePath = findFile($_GET['choix']);
$rawFile = findFile($_GET['choix'], RAWDIR, RAW_EXT);


if (!isset($langSet))
{
	setLang(getSupported($textArray));
	$langSet = true;
}


//if file exists
if ($filePath!==false)
{
	//put the downloadable element in the sidenav
	$postMenu = "<div class='downloadable'>" . 
			I18("Télécharger l'it") . "<br/>" . PHP_EOL .
				downloadDoc($filePath, $rawFile) . 
			"</div>" . PHP_EOL;

	//$canChange not defined yet (gets defined in header_generic.php)
	if (isset($_SESSION['user']) AND $_SESSION['usrlvl']>=2)
	{
		$postMenu .= getUserAction('detail');
	}
}


$pageTitle = $_GET['choix'];
$openbtny = '2px';


//HTML code starts in here
include("header_generic.php"); 


//HTML for the content

//if the file exists
if ($filePath!==false)
{
	echo PHP_EOL . PHP_EOL . "<div id='main'>" . PHP_EOL;

		//cette page montre une it
		instructDisp($filePath);

	echo PHP_EOL . "</div>" . PHP_EOL . PHP_EOL;
}
else // if file not found
{
	echo "<h2 style='color: red'>" . I18("Cette it n'a pas été trouvée") . "</h2>";
}

//end of the HTML contents 

//HTML code ends in here
include("trailer.php");		
?>


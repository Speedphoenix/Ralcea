<?php
//This page shows every it to choose from

if (session_status()==PHP_SESSION_NONE)
	session_start();

//this is to change things in the header, I prefer this to using $_SERVER 
$indexp = true;

include_once "functions.php";
include_once "langfunc.php";

if (!isset($langSet))
{
	setLang(getSupported($textArray));
	$langSet = true;
}

$openbtny = "20px";

if (!isset($postMenu))
	$postMenu = "";

//pour cacher (ou non) les tags
$postMenu .= '	<form id="sTagForm" method="post" action="' . $_SERVER["PHP_SELF"] . '">
			<input type="checkbox" onclick="submitForm(\'sTagForm\');" name="showTag" id="showTag" ' . (isset($_POST['showTag'])?'checked':'') . '/>
			<label for="showTag">' . I18("Montrer les variantes symboliques") . '</label>
	</form>' . PHP_EOL;


include("header_generic.php");

if ($canChange)
{
	echo '<form method="post" action="user_action.php" id="actionForm" enctype="multipart/form-data">' . PHP_EOL;

	echo "<div id='userPos' class='userPos'>" . PHP_EOL;
		echo getUserAction('index');
	echo "</div>" . PHP_EOL;
}

echo '<div id="main">' . PHP_EOL;

	ITMenu();

	listBatches(); //menu des batchs d'it

	listAll(); //enumération des instructions

//change this part to put a nice positive message
echo '<div id="news">' . PHP_EOL;

include "news/news.php";

echo '</div>' . PHP_EOL;

echo '</div>';

if ($canChange)
{
	echo '</form>' . PHP_EOL;
}

include("trailer.php");

?>


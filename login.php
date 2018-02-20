<?php

include_once "langfunc.php";
include_once "functions.php";
include_once "authent.php";

if (session_status()==PHP_SESSION_NONE)
	session_start();

if (isset($_SESSION['user']))
{
	header('location: index.php'); //A CHANGER
}

setLang(getSupported($textArray));

$pageTitle = 'Login';
$errorLine = '';


if (isset($_POST['login'], $_POST['password']))
{
	//log him in
	if (canAuth($_POST['login'], $_POST['password']))
	{
		unset($_POST['login']);
		unset($_POST['password']);
		header('location: index.php'); 
	}
	else
	{
		$errorLine = "<h3>" . I18("Identifiant ou mot de Passe incorrect") . "</h3>";
	}
}

$loginp = true;

include_once "header_generic.php";


echo $errorLine . PHP_EOL;


//This only happens if the user is not logged in yet
//(just got here or input wrong usr/passwd)

echo "<br/><br/><br/>" . PHP_EOL;

//to not be overridden by the sidenav
echo "<form method='post' style='margin-left:250px' action='login.php'>
	<table>
		<tr>
			<td>" . I18("Identifiant") . "</td>
			<td><input type='text' name='login'/></td>
		</tr>
		<tr>
			<td>" . I18("Mot de Passe") . "</td>
			<td><input type='password' name='password'/></td>
		</tr>
		<tr>
			<td><input type='submit' value='" . I18("Valider") . "'/></td>
		</tr>
	</table>
	</form>" . PHP_EOL;

include "trailer.php";
?>

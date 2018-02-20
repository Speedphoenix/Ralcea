<?php

session_start();

include_once "config.php";
include_once "generic.php";

if (isset($_SESSION['user']))
{
	addLog(logmsg('has logged out manually', $_SESSION['user']));

	unset ($_SESSION['user']);
	unset ($_SESSION['usrlvl']);
}

header("location: index.php");

?>

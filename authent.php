<?php

include_once "functions.php";

if (session_status()==PHP_SESSION_NONE)
	session_start();

//returns true if could login, false otherwise
//this function will be removed in time.
function canAuth($login_i, $passwd)
{
	if (!isset($_SESSION['user']))
	{
		$login = htmlspecialchars($login_i);
		$userList = file(USERS);

		foreach ($userList as $elem)
		{
			$user = explode(':', $elem);

			if ($user[0] == $login)
			{
				if (password_verify($passwd, $user[1]))
				{
					addLog(logmsg("Has logged in", $login));

					$_SESSION['user'] = $login;
					$_SESSION['usrlvl'] = $user[2];
					return true;
				}
			}
		}
	}
	else
	{
		//send an error message?
		addLog(logmsg('Tried logging in a second time....', $_SESSION['user']));
		return false;
	}

	return false;
}

//adds a user.
//this function is only used by the admin...
//$password should not be hashed yet
function addUsr($login, $password, $usrlvl)
{
	$user = array();
	$user[0] = $login;
	$user[1] = password_hash($password, PASSWORD_DEFAULT);
	$user[2] = $usrlvl;


	if (!($file = fopen(USERS, 'a')))
		return false;

	fprintf($file, "$user[0]:$user[1]:$user[2]" . PHP_EOL);

	fclose($file);
}

//addUsr('Leonardo', 'Les Schtroumpfs de Pascal', 2);

?>

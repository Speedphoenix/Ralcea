<?php

if (session_status()==PHP_SESSION_NONE)
	session_start();

include_once "config.php";
include_once "generic.php";

function callback_is_file($var)
{
	return is_file(SOURCEDIR . $var);
}

function loadIT()
{

	$itList = array_filter(scandir(SOURCEDIR), 'callback_is_file');

	return $itList;
}

function withoutTag($var)
{
	$tag = strstr($var, '-');
	$tag = substr($tag, 1);
	$tag = strstr($tag, '-', true);

	if ($tag === false OR !in_array($tag, TAGS))
	{
		return $var;
	}
	else
	{
		//might cause problems if $var contains multiple tags
		return str_replace('-' . $tag, '', $var);
	}
}

//returns the filename WITH RELATIVE PATH
//takes the file without tag as a param
function findFile($itname, $dir = SOURCEDIR, $ext = '.pdf')
{
	if (is_array($ext))
	{
		foreach ($ext as $elem)
		{
			$rep = look_for_file($itname, $dir, $elem);

			if ($rep!==false)
				return $rep;
		}
	}
	else
	{
		$rep = look_for_file($itname, $dir, $ext);

		if ($rep!=false)
			return $rep;
	}

	//if we got to this point it means
	//we didn't find it in the normal folder.
	//now to look in the archives
	if (strstr($dir, ARCHIVES)===false)
	{
		$rep = findFile($itname, $dir . ARCHIVES, $ext);

		if ($rep===false)
		{
			//echo an error
		}
		else
		{
			//put in the log file that a file from the archives was loaded
			//the smaller vars ($logged, $user etc...) might not be set yet
			$userstr = isset($_SESSION['user'])?($_SESSION['user'] . ' permission: ' . $_SESSION['usrlvl'] . ' at '):'' . $_SERVER['REMOTE_ADDR'];

			addLog(logmsg("The file $itname ($rep) that is in the archived folder has been accessed"), $userstr);
		}

		return $rep;
	}

	return false;
}

//this function is just to not copy paste in findFile
function look_for_file($itname, $dir, $ext)
{
	$resname = $itname . $ext;
	$filename = $resname;

	if (is_file($dir . $filename))
	{
		return $dir . $filename;
	}

	foreach (TAGS as $elem)
	{
		$position = strpos($resname, '-');
		$filename = substr_replace($resname, '-' . $elem, $position, 0);

		if (is_file($dir . $filename))
		{
			return $dir . $filename;
		}
	}
	return false;
}

//sorts ITs by batch
function sortIT($itList)
{
	$reptab = array();

	foreach($itList as $elem)
	{
		$separated = explode('-', pathinfo(withoutTag($elem), PATHINFO_FILENAME));

		$key;

		if ($separated[0]==='IT')
		{
			$key = $separated[1];
		}
		elseif (isset($separated[1]) AND $separated[1]==='IT')
		{
			$key = $separated[2];
		}
		else
		{
			//it's a special/generic IT
			$key = 'GENERIC';
		}

		if (in_array($key, GENERIC))
		{
			$key = 'GENERIC';
		}

		$reptab[$key][] = $elem;
		
	}

	ksort($reptab);

	return $reptab;
}


//returns an array of IT that contain that certain string
function search($ITarray, $needle)
{
	$rep = array();

	foreach($ITarray as $elem)
	{
		if (strpos($elem, $needle)!==false)
		{
			$rep[] = $elem;
		}
	}
	return $rep;
}


?>

<?php

//note that this function is unrelated to any library you can find outside of this project
//gives the right text depending on the language
function I18($text)
{
	global $textArray;
	if ($textArray)
	{
		return htmlspecialchars(chooseText($textArray, htmlspecialchars($text)));
	}
	else
	{
		return htmlspecialchars($text);
	}
}

//taken from the first comment of php.net/manual/en/class.simplexmliterator.php
//credits to ratfactor at gmail dot com
function xmlToArray($fileName)
{
	$sxi = new SimpleXmlIterator($fileName, null, true);
	return sxiToArray($sxi);
}


//taken from the first comment of php.net/manual/en/class.simplexmliterator.php
//credits to: ratfactor at gmail dot com
function sxiToArray($sxi)
{
	$a = array();
	for ($sxi->rewind();$sxi->valid();$sxi->next())
	{
		if (!array_key_exists($sxi->key(), $a))
		{
			$a[$sxi->key()] = array();
		}

		if ($sxi->hasChildren())
		{
			$a[$sxi->key()][] = sxiToArray($sxi->current());
		}
		else
		{
			$a[$sxi->key()][] = strval($sxi->current());
		}
	}
	return $a;
}


//returns the text in the right language
//takes default if language not available
function chooseText($textArray, $text)
{
	$singleText;

	foreach ($textArray['text'] as $elem)
	{
		if (strcmp($elem['default'][0], $text)===0) //if same strings
		{
	//this variable now contains an array of the right text in all languages
			$singleText = $elem;
			break;
		}
	}

	if (isset($singleText))
	{
		if (isset($singleText[$_SESSION['lang']]))
		{
			return $singleText[$_SESSION['lang']][0];
		}
		elseif (isset($singleText['en']))
		{
			return $singleText['en'][0];
		}
		elseif (isset($singleText['fr']))
		{
			return $singleText['fr'][0];
		}
		else
		{
			return $singleText['default'][0]; //usually french
		}
	}
	else
	{
		return $text;
	}
}


//creates a string with a nice time stamp and everything
function logmsg($message, $user = false)
{
	if ($user===true)
	{
		if (isset ($_SESSION['user']))
			$user = $_SESSION['user'];
		else
			$user = "";
	}
	elseif ($user===false)
	{
		$user = "";
	}

	return "[" . date('D M d H:i:s Y') . "] [$user] " . $message;
}


//adds the string at the end of the log file.
function addLog($string)
{
	if (!$file = fopen (USRLOG, "a"))
		return false;

	fprintf($file, $string . PHP_EOL);

	fclose($file);
}

?>

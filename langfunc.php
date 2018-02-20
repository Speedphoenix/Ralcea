<?php

include_once "config.php";

function getSupported($textArray)
{

	if ($textArray===false)
	{
		return array();//no support
	}

	$rep = array();

	//this is if you decide which languages are supported from the ABR_LANG array
	//you can see/change this in config.php
	if (SUPP_CHOICE=='arr')
	{
		foreach(ABR_LANG as $key => $value)
		{
			$rep[] = $key;
		}
	}

	//this is if you decide which languages are supported from the language.xml file
	//you can see/change this in config.php
	elseif(SUPP_CHOICE=='file')
	{
		foreach($textArray['text'] as $elem)
		{
			foreach($elem as $lang => $texte)
			{
				if ($lang!='default' AND !in_array($lang, $rep))
				{
					$rep[] = $lang;
				}
			}
		}
	}

	return $rep;
}

//sets the session, cookie language. The session must already be started
function setLang($supported)
{

	//10 yrs
	$cookieExpire = 10*365*24*3600;

	if (isset($_GET['lang']) AND in_array($_GET['lang'], $supported))
	{
		$_SESSION['lang'] = $_GET['lang'];
		setcookie('lang', $_GET['lang'], time() + $cookieExpire, null, null, false, true);
	}
	elseif (isset($_GET['from']) AND in_array($_GET['from'], $supported))
	{
		$_SESSION['lang'] = $_GET['from'];
		setcookie('lang', $_GET['from'], time() + $cookieExpire, null, null, false, true);
	}
	elseif (isset($_SESSION['lang']))
	{
		//maybe it's the second time this function is called?
		//no need to do anything
	}
	elseif (isset($_COOKIE['lang']))
	{
		$_SESSION['lang'] = $_COOKIE['lang'];

		//pour réactualiser l'expiration du cookie?
		//setcookie('lang', $_COOKIE['lang'], time() + $cookieExpire, null, null, false, true
	}
	else
	{
		$languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$lang = 'fr';

		foreach($languages as $elem)
		{
			if (in_array($elem, $supported))
			{
				$lang = $elem;
				break;
			}
		}

		$_SESSION['lang'] = $lang;
		setcookie('lang', $lang, time() + $cookieExpire, null, null, false, true);
	}
}

?>

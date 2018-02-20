<?php

///this is a script to take all text from the directories/files in params and put it in a .xml file
//the first parameter must be the output file
//the params after that should be directories or files

include_once "config.php";

libxml_use_internal_errors(true);
$already = array();
$xml;


//returns true if the text isn't valid, false otherwise
function isInvalid($text)
{
	if (empty($text))
		return true;
	elseif (!preg_match("/[a-z]/i", $text))
		return true;
	else
		return false;
}

//ajoute dans le fichier xml
function add($what)
{
	global $xml;
	$isnew = true;

	foreach($xml->text as $elem)
	{
		if (strcmp($elem->default,$what)===0) // === ne marche pas, == marche...
		{
			$isnew = false;
			break;
		}
	}

	if ($isnew)
	{
		$new = $xml->addChild('text');
		$new->addChild('default', $what);
		$new->addChild('fr', $what);
	}
}

//regarde chaque instance de I18 et prend le texte
//verifie si pas déjà pris, puis add
function parse($filename)
{
	global $already;
	$lines = file($filename);
	
	foreach($lines as $elem)
	{
		if (strpos('//', $elem)<strpos('I18(', $elem))
			continue;

		$curr;
		$next = strstr($elem, 'I18(');


		//tant qu'il y a des instances de I18 restantes
		while ($next!==false)
		{
			$curr = $next;
		
		//pour après s'il y a d'autres instances de I18 dans la même ligne
			$next = substr($curr, 5);
			if (strpos('//', $next)<strpos('I18(', $next))
				$next = false; //continue 2;//the 2 is not necessary here
			else
			{
				$next = strstr($next, 'I18(');
				if ($next!==false)
				{
					echo $curr,  'oh non', PHP_EOL; //WHAT
				}
			}


			// pour supporter les chaines "..." et '...'
			$char;
			if (strpos($curr, '"') == 4)
			{
				$char = '"';
			}
			elseif (strpos($curr, "'") == 4)
			{
				$char = "'";
			}

			if (isset($char))
			{
				//on coupe I18("
				$curr = substr($curr, 5);
				
				//on prend que ce qui vient avant le ' ou "
				$curr = strstr($curr, $char, true);

				if (isInvalid($curr))
				{
					echo "have an invalid text, " . $curr . " this line: " . PHP_EOL . $elem . PHP_EOL . "this file: " . $filename;
				}
				elseif (!in_array($curr, $already))
				{
					//on ajoute le le nouveau
					$already[] = $curr;
					add(htmlspecialchars($curr));
				}
			}
		}
	}
}

//cette partie a été mise dans une fonction dans 
//un effort contre un bug d'éditeur de texte
function callMain()
{
	global $argv;
	global $xml;

	if (file_exists($argv[1]))
	{
		$xml = simplexml_load_file($argv[1]) or die("error loading xml");
	}
	else
	{
		$xml = simplexml_load_string('<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>' . PHP_EOL . '<I18>' . PHP_EOL . PHP_EOL . '</I18>');
	}
	
	//l'argv 0 est le nom de ce fichier (gettextscript.php), 
	//l'argv 1 est le nom du fhichier xml , 
	//les argv suivant sont les fichiers d'où prendre le texte
	for ($i=2;$i<count($argv);$i++)
	{
		if (in_array($argv[$i], IGNOREI18))
		{
			continue;
		}

		if (is_dir($argv[$i]))
		{
			$files = scandir($argv[$i]);
	
			foreach ($files as $elem)
			{
				if (is_file($elem) AND (!in_array($elem, IGNOREI18) AND $elem[0]!='.' ))
				{
					parse($argv[$i] . $elem);
				}
			}
		}
		elseif (is_file($argv[$i]) AND $argv[$i][0]!='.')
		{
			parse($argv[$i]);
		}
	}
	
	$xml->asXML($argv[1]);
}

callMain();

?>


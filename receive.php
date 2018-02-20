<?php

include_once "config.php";

//this file is the receiver of somemone submitting the translation form

$xml;
//open xml
if (file_exists(TEXTFILE))
{
	$xml = simplexml_load_file(TEXTFILE) or die("error loading xml");
}
else //this shouldn't happen
{
	$xml = simplexml_load_string('<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>' . PHP_EOL . '<I18>' . PHP_EOL . PHP_EOL . '</I18>');
}

$worked = false;

if (isset ($_POST['to']))
{

	$to = $_POST['to'];

	//making a foreach( as $indice =>) would set every $indice to "text"
	$indice = 0;
	
	foreach ($xml->text as $text)
	{
		//if the translator entered text last page we take it
		//else we don't change/add anything
		//and the website will take a default value
		if (!empty($_POST[$indice]))
		{
			if (isset ($text->{$to}))
			{
				unset ($text->{$to});
			}

			$text->addChild($to, htmlspecialchars($_POST[$indice]));
		}

		$indice++;
	}

	$xml->asXML(TEXTFILE);
	$worked = true;

}

header('Location: translate.php?worked=' . $worked);

?>

<?php

if (session_status()==PHP_SESSION_NONE)
	session_start();

$textArray;

include_once "config.php";
include_once "generic.php";
include_once "langfunc.php";


if (file_exists(TEXTFILE))
{
	//to put it all in a nice array
	$textArray = xmlToArray(TEXTFILE);
}
else
{
	//hope this never happens?
	echo "<!DOCTYPE html><h1> TEXT FILE NOT FOUND (" . TEXTFILE . ") called from translate.php";
	$textArray = false;
	exit("TEXT FILE NOT FOUND (" . TEXTFILE . ") called from translate.php");
}

//has a default value
$from = 'fr';
$to;
$supported = getSupported($textArray);

//check the values received (if there are any)
if (isset($_POST['from']) AND $_POST['from']=='en')
{
	$from = 'en'; //only possibility other than fr
}

//in_array not necessary here...
if (isset($_POST['to']))
{
	if (in_array($_POST['to'], $supported))
	{
		$to = $_POST['to'];
	}
	elseif ($_POST['to']==='new')
	{
		$to = $_POST['newlang'];
	}
}



//set the session language etc
setLang($supported);

echo "<!DOCTYPE html>
	<head>
		<title>" . I18("Traduction") . "</title>
		<meta charset='UTF-8'>
		<link rel='stylesheet' href='style.css'>
	</head>";

echo "<body>";

if (isset($_GET['worked']))
{
	if ($_GET['worked']==false)
	{
		echo "<h1 style='color:red'>" . I18('La sauvegarde des traductions a échoué') . "</h1>";
	}
	else
	{
		echo "<h2 style='color:green'>" . I18('La sauvegarde des traductions a réussi') . "</h1>";
	}
}


//choose languages
echo '<div>' . PHP_EOL;
	//from which lang to translate (optional?)
echo '	<form method="post" action="' . $_SERVER["PHP_SELF"] . '">
			<p>' . I18("Choisissez depuis quelle langue traduire") . '</p>
			<select name="from">';

			echo '<option value="fr" ';
			if ($_SESSION['lang']=='fr')
				echo 'selected';
			echo '>' . ABR_LANG['fr'] . '</option>' . PHP_EOL;

			echo '<option value="en" ';
			if ($_SESSION['lang']=='en')
				echo 'selected';
			echo '>' . ABR_LANG['en'] . '</option>' . PHP_EOL;
		
echo '		</select>';


	//to which lang
echo '		<p>' . I18("Choisissez vers quelle langue traduire") . '</p>
			<select onchange="changedSelect()" id="selectto" name="to">';
		
		foreach($supported as $elem)
		{
			echo '<option value="' . $elem . '"';
	
			if (isset($to) AND $to===$elem)
			{
				echo ' selected';
			}
			echo '>' . ABR_LANG[$elem] . '</option>' . PHP_EOL;
		}

		if (isset($_POST['to']) AND $_POST['to']==='new')
		{
			echo '<option value="new" selected >' . ABR_LANG[$to] . '</option>' . PHP_EOL;
		}
	//can also create a new lang
	//make sure the language name entered is in accordance to conventions
		echo '<option value="new">' . I18("Nouvelle langue") . '</option>' . PHP_EOL;


echo '		</select>';

//textbox if new lang
echo '			<br>
			<input id="newlangbox" style="display:none"	 type="text" name="newlang" value="' . ((isset($_POST['newlang']))?$_POST['newlang']:'') .'"/>
			<br>

			<input type="checkbox" name="hideAlready" id="case"' . (isset($_POST['hideAlready'])?'checked':'') . '/>
			<label for="case">'. I18('Cacher les champs ayant déjà une traduction') .'</label>
			<br>

			<input type="submit" value="' . I18("Valider") . '"/>
		</form>
		<br><hr><br>';


//the script to hide/show the textbox
?>


<script>
function changedSelect(){
	if (document.getElementById("selectto").value=="new")
	{
		document.getElementById("newlangbox").style.display="block";
	}
	else
	{
		document.getElementById("newlangbox").style.display="none";
	}
}
changedSelect(); //call the funciton at start
</script>

<?php
echo '	</div>';

//the translation part
if (isset($to))
{
echo " <form method='post' action='receive.php'>";

	$formContents = "";
	$count = 0;
	$shownCount = 0;
	foreach($textArray['text'] as $elem)
	{
		$fromVal;
		$toVal;

		if (array_key_exists($from, $elem))
			$fromVal = $elem[$from][0];
		else
			$fromVal = $elem['default'][0];

		if (array_key_exists($to, $elem))
			$toVal = $elem[$to][0];
		else
			$toVal = '';

		if (!isset($_POST['hideAlready']) OR empty($toVal))
		{
			$shownCount++;

			$formContents .= "<hr>" . PHP_EOL . "<div class='doubleHalf'>" . PHP_EOL;


			$formContents .= "<div class='Half'><p>" . $fromVal . "</p></div>" . PHP_EOL;
			$formContents .= "<div class='Half'><textarea name='" . $count . "' rows='4' cols='70' wrap='hard'>" . $toVal . "</textarea></div>" . PHP_EOL;

			$formContents .=  "</div>" . PHP_EOL . "<br style='clear:both'>" . PHP_EOL;
		}

		$count++;
	}

echo "	<input type='hidden' name='to' value='" . $to . "'/>";

	if ($shownCount==0)
	{
		echo "<h3>" . I18("Tous les champs ont déjà une traduction possible pour cette langue") . "</h3>";
	}
	else
	{
		echo "<div style='text-align:center'>
	<input type='submit' value='" . I18("Valider les modifications") . "' />
</div>";

		echo $formContents;

		echo "<div style='text-align:center'>
	<input type='submit' value='" . I18("Valider les modifications") . "' />
</div>";
	}


echo PHP_EOL . "</form>" . PHP_EOL;
}

echo "</body>
</html>";

?>

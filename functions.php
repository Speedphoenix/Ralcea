<?php

//this file contains many of the functions specific to this project
//mostly used to generate the right HTML

$textArray;

include_once "config.php";
include_once "generic.php";
include_once "langfunc.php";
include_once "itmanage.php";

$itList = loadIT();
$orderedIT = sortIT($itList);


//text in different languages
if (file_exists(TEXTFILE))
{
	$textArray = xmlToArray(TEXTFILE);
}
else
{
	$textArray = false;
}


//returns the html code for a select menu of possible languages
function langChoice()
{
	global $supported;

	global $detailp; //is usefull when on the detail page

	$rep = '';

	$rep .= '<form method="get" id="langForm" action="' . $_SERVER["PHP_SELF"] . '">
		<select onchange="submitForm(\'langForm\')" name="lang">';

	foreach($supported as $elem)
	{
		$rep .= '<option value="' . $elem . '"';

		if ($_SESSION['lang']===$elem)
		{
			$rep .= ' selected';
		}
		$rep .= '>';

		//we usually want 'Français' instead of 'fr' don't we
		$rep .= (isset(ABR_LANG[$elem])?ABR_LANG[$elem]:$elem);
		$rep .= '</option>' . PHP_EOL;
	}

	$rep .= '	</select> ';

		//so that we keep the current IT (and don't redirect afterwards)
		if (isset($detailp))
		{
			$rep .= '<input type="hidden" name="choix" value="' . $_GET['choix'] . '"/>';
		}
$rep .= '	</form>';

	return $rep;
}


//select menu of every it
function listSelect()
{
	global $itList;
	
echo"
	<form method='get' action='detail.php", JUMP_PDF?"#instruction'":"'", ">
		<select name='choix' class='ITSelect' id='selectITlist' onchange='changedSelect(\"selectITlist\", \"submitITselect\", true)'>


		<option value=0>" . I18('-Choisissez-') . "</option>" . PHP_EOL;

	foreach($itList as $elem)
	{
		$name = basename($elem, '.pdf');
		if (!isset($_POST['showTag']))
		{
			$name = withoutTag($name);
		}

		if (!in_array($name, IGNOREIT))
		{

			echo "<option value='$name'";

			///CHANGE THIS FOR THE TAG
			// si on arrive sur la page avec un choix
			if (isset($_GET['choix']) AND $_GET['choix']==$name)
			{
				echo " selected='selected'";
			}

			echo ">$name</option>" . PHP_EOL;
		}
	}


	//the submit button shows up when you make a choice
	echo"
		</select>
		<input type='submit' style='display:none' id='submitITselect' value='" . I18("Consulter") . "' />
	</form>";
}


//makes a menu to choose batches alphabetically
function ITMenu()
{
	global $orderedIT;
	global $canChange;

	echo "<div class='ITMenu'>
" . "<p style='margin-bottom:2px'>" . I18("Visualiser par plateformes:") . "</p>
	<ul>" . PHP_EOL;

echo "	<li>" . PHP_EOL;
nameBatch('GENERIC', $canChange);
echo "	</li>" . PHP_EOL;

	$bigNames = false;
	$letters = array();

	//take the letters and separate the misc
	//make sure we only take letters that are used
	foreach ($orderedIT as $batchname => $batch)
	{
		if ($batchname==='GENERIC') continue;

		if (strlen($batchname)!=3 AND !in_array($batchname, NOTMISC))
		{
			$bigNames = true;
			continue;
		}

		if (!in_array(strtoupper($batchname[0]), $letters))
		{
			$letters[] = strtoupper($batchname[0]);
		}
	}

	sort($letters);

	//display the letters on the menu
	foreach ($letters as $elem)
	{
	echo "	<li id='_letter$elem'>
			<a href='javascript:void(0)' onclick='toggle(\"letter$elem\", \"_letter$elem\");'>$elem</a>		
		</li>" . PHP_EOL;
	}

	//add the misc
	if ($bigNames)
	{
	echo "	<li id='_misc'>
			<a href='javascript:void(0)' onclick='toggle(\"misc\", \"_misc\");'>Misc.</a>	
		</li>";
	}


echo "	</ul>

</div>";

}


//a menu to select a batch
//(lists only the batch name, not the actual contents)
function listBatches()
{
	global $orderedIT;
	global $canChange;

	echo "<div class='batchContainer'>";

	$byOrder = array();
	$bigNames;

	foreach ($orderedIT as $batchname => $batch)
	{
		//don't display generic, it was already put in the ITMenu
		if ($batchname==='GENERIC') continue;

		//if it count's as a misc separate it from the rest
		if (strlen($batchname)!=3 AND !in_array($batchname, NOTMISC))
		{
			$bigNames[] = $batchname;
			continue;
		}


		$byOrder[strtoupper($batchname[0])][] = $batchname;

	}

	//sort alphabetiaclly
	ksort($byOrder);

	//letters first
	foreach ($byOrder as $letter => $batchnames)
	{
	echo "<div style='display:none' id='letter$letter'>" . PHP_EOL;

		foreach ($batchnames as $batchname)
		{
			nameBatch($batchname, $canChange);
		}

	echo "</div>" . PHP_EOL;
	}

	//misc
	if (!empty ($bigNames));
	{
	echo "<div style='display:none' id='misc'>" . PHP_EOL;

		foreach ($bigNames as $batchname)
		{
			nameBatch($batchname, $canChange);
		}

	echo "</div>" . PHP_EOL;
	}

	echo "</div>" . PHP_EOL;

}


//just so as not to copy paste in function listBatches
//properly shows a batch name
function nameBatch($batchname, $canChange)
{
	echo " <div id='_$batchname' class='nameContainer'>" . PHP_EOL;

/*
	if ($canChange)
	{
		echo "<input type='checkbox' name='batch[]' value='$batchname'/>" . PHP_EOL;
	}
*/

		echo "	<h3 style='display:inline-block'><a href=";
		if (JUMP_BATCH)
		{	//when you click on a batch it jumps down to it
			echo "'#$batchname'";
		}
		else
		{	//to ensure the onclick works
			echo "'javascript:void(0)'";
		}

		echo " onclick='show(\"$batchname\", \"_$batchname\");' class='collapser'>$batchname</a></h3>
	</div>";
	
}


//shows one batch of ITs.
function dispBatch($batchname, $tab)
{
	global $canChange;

	//might be a good idea to put a checkbox for the whole batch 
	//but need to be careful not to override the others in the list.
	//Make a js 'check all'

echo " <div class='instructContainer' id='$batchname'>
		<h3><a href='javascript:void(0)' onclick='collapse(\"$batchname\", \"_$batchname\");' class='collapser'>$batchname</a></h3>
		<div class='instructions'>" . PHP_EOL;

	foreach ($tab as $elem)
	{
		$name = basename($elem, '.pdf');


		if (!isset($_POST['showTag']))
		{
			$name = withoutTag($name);
		}

		if (!in_array($name, IGNOREIT))
		{
			echo PHP_EOL . '<div class="instruct">' . PHP_EOL;
			if ($canChange)
			{
				//we don't know if name already has tag removed or not
				echo "<input type='checkbox' name='IT[]' value='" . withoutTag($name) . "'/>";
			}

		echo PHP_EOL . "	<a href='detail.php?choix=$name", JUMP_PDF?"#instruction'":"'";

			//should the link open in a new tab/window
			if (NEW_TAB)
			{
				echo " target='_blank'";
			}

		echo ">$name</a>
</div><br/>" . PHP_EOL;
		}	
	}

echo "
		</div>
	</div>";
}


//enumeration/list of every batch of work it
function listAll()
{
	global $orderedIT;

	//to make sure this comes first
	//not so usefull anymore since they're not all open at start anyways..
	dispBatch('GENERIC', $orderedIT['GENERIC']);

	foreach($orderedIT as $name => $elem)
	{
		if ($name=='GENERIC') continue;

		dispBatch($name, $elem);
	}
}


//creates a clickable that would download/open the file in another tab
function downloadDoc($pdfPath, $rawPath = false)
{
	$pos = strrpos($pdfPath, '.');
	return	"<div style='columns:2'>
	<a href='$pdfPath' download='" . substr_replace($pdfPath, '_copy', $pos, 0) . "'>pdf</a>
	<a href='$rawPath' download='" . substr_replace($rawPath, '_copy', $pos, 0) . "'>" . I18("Original") . "</a>
</div>";
}


//shows one pdf in an embed
function instructDisp($docPath)
{
	echo "
		<div id='instruction'>
				<embed src='" . $docPath . "' id='instructDisp' class='instructDisp' type='application/pdf'>
		</div>";
}


//adds the sidenav (and the open sidenav button)
//$early contains non-generic things that should be at the start of the nav
//$late contains non-generic things that should be at the end of the nav
function sideNav($early, $late)
{
	global $openbtny;
	global $logged;
	global $indexp;

//le bouton pour ouvrir la sidenav
echo "<a href='javascript:void(0)'";
	if (isset($openbtny))
	{
		echo " style='top:$openbtny'";
	}
echo  " class='openbtn' onclick='openNav()'>&#9776;</a>" . PHP_EOL;


//start of the sideNav Menu
echo "<div id='sidenav' class='sidenav'>
		<a href='javascript:void(0)' class='closebtn' onclick='closeNav()'>&times;</a>

		$early

		<a href='#'>" . I18("Ancien site") . "</a>

		<a href='index.php"; //menu principal
		if (isset($_GET['choix']))
		{
			echo '?choix=' . $_GET['choix'];
		}
echo "'>" . I18("Menu principal") . "</a>" . PHP_EOL;

	if (!$logged)
	{
		echo "	<a href='login.php'>" . I18("Administration") . "</a>";
	}

	if (!isset($indexp))
	{
		//menu déroulant d'it
		//for index.php it goes to the ITMenu
		listSelect();
	}

echo 	$late;

echo "		</div>" . PHP_EOL;

}


//returns the html code for the admin user box
function getUserAction($type)
{
	$rep = "";

	$question;

	switch ($type)
	{
		case 'index':
		$question = I18("Que voulez-vous faire?");
	break;

		case 'detail':
		$question = I18("Que voulez-vous faire?");
	break;
	}

	$rep .= PHP_EOL . "<div class='userAction'>
	<p style='margin:0'>$question</p>" . PHP_EOL;

	$rep .= "<select name='actionChoice' id='actionChoice'
onchange='changedSelect(\"actionChoice\", \"ITupload\", \"upload\", \"update\")'>" . PHP_EOL;

	foreach (ACTIONS as $key => $elem)
	{
		$rep .= " <option value='$key'>$elem</option>" . PHP_EOL;
	}

	$rep .= "</select>
	<div id='ITupload'>
		<input type='hidden' name='MAX_FILE_SIZE' value='" . MAXFSIZE . "'/>
		<input type='file' name='newIT' id='newIT'/>
	</div>" . PHP_EOL;

	if ($type==='detail' AND isset($_GET['choix']))
	{
		$rep .= "<input type='hidden' name='IT[]' value='" . withoutTag($_GET['choix']) . "'>";
	}

	$rep .= "<input type='submit' value='" . I18("Valider") . "'/>
</div>";

	return $rep;
}


?>

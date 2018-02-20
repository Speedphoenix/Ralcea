<?php

if (session_status()==PHP_SESSION_NONE)
	session_start();

include_once "itmanage.php";
include_once "generic.php";

//creates an array of ITs depending on what was checked in $_POST
//returns an assotiative array (this is to avoid duplicates)
//key = IT name without tag
//value = true
function getITarray($orderedIT)
{
	$rep = array();

	if (isset($_POST['IT']))
	{
		foreach ($_POST['IT'] as $name)
		{
			$rep[$name] = true;
		}
	}

	if (isset($_POST['batch']))
	{
		foreach ($_POST['batch'] as $name)
		{
			foreach ($orderedIT[$name] as $elem)
			{
				///if already had it it overrides it
				$rep[withoutTag(basename($elem, '.pdf'))] = true;
			}
		}
	}
	return $rep;
}


//moves each IT contained in $tab to the archives folder (both raw and pdf)
function archive($tab)
{
	//to put in log file
	$moved = array();

	if (!is_dir(SOURCEDIR . ARCHIVES))
	{
		mkdir(SOURCEDIR . ARCHIVES);
	}

	if (!is_dir(RAWDIR . ARCHIVES))
	{
		mkdir(RAWDIR . ARCHIVES);
	}

	foreach ($tab as $elem => $nothing)
	{
		$pdfFile = findFile($elem);
		$rawFile = findFile($elem, RAWDIR, RAW_EXT);

		if (is_file($pdfFile))
		{
			//move to the archives dir
			if (rename($pdfFile, SOURCEDIR . ARCHIVES . "$elem." . pathinfo($pdfFile, PATHINFO_EXTENSION)))
			{
				$moved[] = $pdfFile;
			}
			else
			{
				$moved[] = "tried $pdfFile but couldn't";
			}
		}

		if (is_file($rawFile))
		{
			if (rename($rawFile, RAWDIR . ARCHIVES . "$elem." . pathinfo($rawFile, PATHINFO_EXTENSION)))
			{
				$moved[] = $rawFile;
			}
			else
			{
				$moved[] = "tried $rawFile but couldn't";
			}
		}
	}

	if (!empty ($moved))
	{
		addLog(logmsg("Archived " . implode(' ', $moved) , true));
	}
}

//puts all ITs contained in $tab in the TRASHDIR directory
//is very similar to the archive func...
//not very clean, I know
//this function looks at the keys of the $tab array only (values are useless)
function deleteIT($tab)
{
	//to put in log file
	$moved = array();
	$deleted = array();

	if (!is_dir(RAWDIR . TRASHDIR))
	{
		mkdir(RAWDIR . TRASHDIR);
	}

	foreach ($tab as $elem => $nothing)
	{
		$pdfFile = findFile($elem);
		$rawFile = findFile($elem, RAWDIR, RAW_EXT);

		if (is_file($rawFile))
		{
			$dir = RAWDIR . TRASHDIR . "$elem/";
			$num;

			if (!is_dir($dir))
			{
				mkdir($dir);
				$num = 0;
			}
			else
			{
				$num = 0; //end(explode('_', end(scandir($dir))));

				while (is_file($dir . "$elem." . pathinfo($rawFile, PATHINFO_EXTENSION) . "_$num"))
				{
					$num++;

					//never know
					if ($num>10000)
					{
						addLog(logmsg("$dir is saturated!!!!", true));
						die ("the trash directory $dir is saturated, please call a system admin");
					}
				}
			}

			if (rename($rawFile, $dir . "$elem." . pathinfo($rawFile, PATHINFO_EXTENSION) . "_$num"))
			{
				$moved[] = $rawFile;
			}
			else
			{
				$moved[] = "tried $rawFile but couldn't";
			}
		}

		if (is_file($pdfFile))
		{
			if (unlink($pdfFile))
			{
				$deleted[] = $pdfFile;
			}
			else
			{
				$deleted[] = "tried $pdfFile but couldn't ";
			}
		}
	}

	if (!empty ($moved))
	{
		addLog(logmsg("Moved to trash " . implode(', ', $moved) . "; and Deleted " . implode(', ', $deleted) , true));

		return true;
	}
	else
	{
		return false;
	}
}


//check la nomenclature, le format etc
function checkName($filename, &$error)
{
	if (!in_array(pathinfo($filename, PATHINFO_EXTENSION), ACCEPT_EXT))
	{
		$error = I18("Le fichier n'est pas au bon format");
		return false;
	}

	$separated = explode('-', $filename);

	if ($separated[0]!=='IT'
//	OR	in_array($separated[1], TAGS)
	OR	strlen($separated[1])!=3	//client/offer name?
		)
	{
		$error = I18("Le fichier ne respecte pas la nomenclature d'une IT");
		return false;
	}

	return true;
}


//checks if the upload is possible and accepts the file
function checkUpload(&$error, $override = true)
{
	$file = $_FILES['newIT'];
	$name = $file['name'];

	if (!is_uploaded_file($file['tmp_name']))
	{
		$error = I18("Aucun fichier reçu");
		return false;
	}

	if ($file['size'] >= MAXFSIZE)
	{
		$error = I18("Le fichier est trop lourd");
		return false;
	}

	if (!checkName($name, $error))
	{
		return false;
	}


	//check if file exists already

	if ($override)	
	{
		//deleteIT takes the keys of an array (not the value)
		$nameArray = array(pathinfo($name, PATHINFO_FILENAME) => true);
		if (deleteIT($nameArray))
		{
			$error = I18("Le fichier existant a été enlevé");
			addLog(logmsg("the file $name has been updated, old one moved to trash", true));
		}
		else
		{
			$error = I18('IT existante non trouvée. Faites "Ajouter une nouvelle IT" pour la créer');
			return false;
		}
	}
	else
	{
		if (!is_file(findFile(pathinfo($name, PATHINFO_FILENAME), RAWDIR, RAW_EXT)))
		{
			addLog(logmsg("a new file has been added: $name", true));
		}
		else
		{
			$error = I18('IT existante trouvée. Faites "Mettre à jour une IT" pour supprimer la précédente');
			return false;
		}
	}

	//accept the upload
	if (move_uploaded_file($file['tmp_name'], RAWDIR . $name))
	{
		$error .= PHP_EOL . I18("Le fichier a été ajouté avec succès");
	}
	else
	{
		$error .= PHP_EOL . I18("Le fichier n'a pas pu être ajouté");
		addLog(logmsg("Error uploading the file. Error code of the upload: " . $file['error'], true));
		return false;
	}


	//put a flag for the cron d
	if ($flag = fopen(RAWDIR . CONVERTFLAG, 'w'))
	{
		$error .= PHP_EOL . I18("Le fichier sera visible dans les prochaines minutes");

		fclose($flag);
	}
	else
	{
		$error .= PHP_EOL . I18("Le fichier sera visible au plus dans une heure");
	}

	return true;
}

?>

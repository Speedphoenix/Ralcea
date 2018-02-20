<?php

/*
 *	This website was written by Leonardo Jeanteur 
 *	Some snippets might have ben taken from outside sources that should be
 *	stated in the comments
 */


include_once "generic.php";

//name of the whole project
define ("PROJECT_NAME", "Ralcea");


/*
 *	From here on is everything concerning users (admins)
 *	and what they do, can/can't do
 */

//the file that contains users and hashes of the passwords.
//this will be changed in time when the login/auth will be updated
define ("USERS", ".htpasswd");

//different permissions levels
//in header_generic.php level >= 2 is allowed to change things
define ("USRLVL", array(
	0 => 'no',		//no permissions / false
	1 => 'small',	//can view add comments?
	2 => 'admin',	//can add/rm/mv files? //only one used atm
				));

//what are the actions that an admin can take in index.php?
define ("ACTIONS", array(
	'archive' => I18('Archiver la séléction'),
	'delete' => I18('Supprimer la séléction'),
	'upload' => I18('Ajouter une nouvelle IT'),
	'update' => I18('Mettre à jour une IT'),
				));

//the maximum size of a raw IT file. 16MB should be enough
define ("MAXFSIZE", 16000000);

//the filename of the flag created for the immediate convertion of a new upload
define ("CONVERTFLAG", "flag.convert");

//the possible extensions when accepting an upload
//some ITs already on the server might have different extensions
//but are deprecated
define ("ACCEPT_EXT", array(
	'doc',	'docx',	'rtf',
	'txt',	'xls',	'xlsx',
/*	'ppt',	'pptx',*/
				));

//the file containing all the log of every action by user
define ("USRLOG", "users_log.log");



/*
 *	From here on is everything concerning ITs already on the server
 *	Where to find them etc..
 */


//the directory that contains both the pdf source and raw source dirs
define ("ITDIR", "");

//the directory containing every work instruction (pdf format)
define ("SOURCEDIR", ITDIR . "it_pdf/");

if (!is_dir(SOURCEDIR))
	mkdir(SOURCEDIR);

//the directory containing every work instruction (raw (doc) format)
define ("RAWDIR", ITDIR .  "it_raw/");

if (!is_dir(RAWDIR))
	mkdir(RAWDIR);

//the archives directory, contains obsolete/out of use ITs
define ("ARCHIVES", "archives/");

//the trash direcory, contains deleted ITs (one folder per IT)
define ("TRASHDIR", "trashes/");

//all the possible extensions for raw files (that could already be here)
define ("RAW_EXT", array(
	'.doc',	'.docx',
	'.pdf',	'.rtf',
	'.xls',
 //starting here they are not used...
	'.txt',	'.docm',
	'.csv',	'.pptx',
	'.ppt',	'.xlsx',
				));



/*
 *	From here on is things concerning the display of ITs
 *	(how to group them, what tags to remove...)
 */

//an array of batchnames that should be counted as normal batches
//(even if the name is too long)
define ("NOTMISC", array(
				));

//an array of generic tags/names
define ("GENERIC", array(
	'GENERIQUE',
	'generique',
	'GENERIC',
	'generic',
	'GEN',
				));

define ("TAGS", array(
				));

// array containing the files  (WITHOUT EXTENSION) to ignore when listing
// templates for example. note that these are not in the archives dir
define ("IGNOREIT", array(
				));

//should the links on the main menu open the ITs in a new tab?
define ("NEW_TAB", false);

//should the page scroll down to the pdf IT when linking to detail.php
define ("JUMP_PDF", true);

//should the page scroll down to a batch of ITs when opening it
define ("JUMP_BATCH", false);




/*
 *	From here on is everything concerning the language part of the website
 */

//files to ignore when running the gettextscript.
//(they might contain 'I18' but you don't want to read that)
define ("IGNOREI18", array(
	'gettextscript.php',	//important
//	'config.php',
	'language.xml',
	'README.txt',
	'convertNew',
	'../',
	'..',
	'generic.php',
	'style.css',
	'it_raw/',
	'it_raw',
	'it_pdf/',
	'it_pdf',
				));


//the file that contains all the text in different languages
define ("TEXTFILE", "language.xml");

//associative array:
//key	= abreviation
//value	= language name 
define ("ABR_LANG", array(
	'fr' => 'Français',
	'en' => 'English',
//	'de' => 'Deutsch',
	'it' => 'Italiano',
				));

//this defines if the supported languages are taken 
//depending on the content of TEXTFILE or from the $ABR_LANG array
//choices are:
//'arr' to take from the array in config.php
//'file' to take from the file in config.php
define ("SUPP_CHOICE", "arr");


//the folder that contains the flags (by country)
//a flag filename is: country_flags/png/ISO3166-1_alpha-2_country_code.png
define ("C_FLAGS", "country_flags/png/");

//associative array:
//key	= language abreviation
//value	= flag file name
define ("LANG_FLAG", array(
	'fr' => 'fr.png',
	'en' => 'gb.png', //this is important
	'it' => 'it.png',
	'de' => 'de.png',
				));



/*
 *	From here on is other information/defined constants
 */

//le/les destinataires (séparés par des virgules) des mails feedback
define ("MAIL_FEED", "example@gmail.com");

//le fichier csv qui contient tout le feedback des IT
define ("FEEDBACKFILE", "feedback/feedback.csv");

//last change done to the website. Might be out of date :)
define ("LAST_CHANGE", "20/02/2018");

//the filename of the logo
define ("LOGO" , "Some_Logo.png");


//a call to the convertNew script without any actual conversion
//(just check file timestamps) takes roughly 2 mins 20 sec

//command line to call to convert every new file
// ./convertNew.sh it_pdf/ it_raw/*

//command line to call to convert after checking for a flag
// ./convertNew.sh it_raw/flag.convert it_pdf/ it_raw/*

//command line to call to get every I18 to language.xml
// php -f gettextscript.php language.xml *.php

//you can also do that simply by running ./gettext.sh
?>

<?php
//this file contains all the part until the div id="main"
//and all the javascript. special thanks to Internet explorer


if (session_status()==PHP_SESSION_NONE)
	session_start();

include_once "functions.php";
include_once "langfunc.php";


//loading some variables so as not to copy longer lines every time
$logged = false;
$canChange = false;

if (isset($_SESSION['user']))
{
	$logged = true;
	$user = $_SESSION['user'];
	$usrlvl = $_SESSION['usrlvl'];
	if ($usrlvl>=2)
		$canChange = true;
}

//$textArray contains the right array (from functions.php)
$supported = getSupported($textArray);

if (!isset($langSet))
{
	setLang($supported);
}

if (!isset($pageTitle))
{
	$pageTitle = PROJECT_NAME;
}

//maintenant $_SESSION['lang'] contient la valeur de langue du visiteur

echo '<!DOCTYPE html>' . PHP_EOL;

//cette page est rédigée en chamicuro, voulez-vous la traduire?
echo '<html lang="' . $_SESSION['lang'] .'">';

echo '<head>' . PHP_EOL;

echo '<title>' . $pageTitle . '</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="style.css" >' . PHP_EOL;

//is useless... thanks internet explorer!
//echo '	<script src="collapse.js"></script>';

echo '</head>' . PHP_EOL;


//start of body
echo '<body>';
echo '	<div id="head">';

if (!isset($preMenu))
	$preMenu = '';

if (!isset($postMenu))
	$postMenu = '';

//choix des langues
$preMenu .= langChoice();

//the sideNav Menu
echo "<div style='z-index:3'>"; //for the z-index bug on IE
	sideNav($preMenu, $postMenu);
echo "</div>" . PHP_EOL;
//end of the sidenav menu

echo "<div id='IEShaming'>
	<p>" . I18("Optimisé pour Firefox/Chrome") . "</p>
</div>";

//display the error/message from an upload if needed
if (isset($_SESSION['error']))
{
	echo PHP_EOL . "<p class='uploadMsg'>" . $_SESSION['error'] . "</p>";

	unset ($_SESSION['error']);
}


if ($logged)	//add log out button
{
	echo "<div class='loggedBox'>
	<a href='logout.php'>" . I18("Deconnexion") . "</a>
</div>";
}

	//you can change these in config.php. especially usefull for en=>gb
if (isset(LANG_FLAG[$_SESSION['lang']]))
	$imgFile = C_FLAGS . LANG_FLAG[$_SESSION['lang']];
else
	$imgFile = C_FLAGS . $_SESSION['lang'] . ".png";

if (file_exists($imgFile))
{
echo "	<img class='language' src='$imgFile' alt='" . ABR_LANG[$_SESSION['lang']]. "'>";
}


//specific to index page
if (isset($indexp))
{
	echo "<img class='logo' src='" . LOGO . "' alt='Some_Logo.png'>";
}



//the title
if (isset($detailp))
{
	echo "	<h2 id='title' style='font-size:4vmin' class='light'>" . PROJECT_NAME . "</h2>" . PHP_EOL;
}
elseif (isset($indexp))
{
	echo "	<h1 id='title' style='font-size:7vmin'>" . PROJECT_NAME . "</h1>" . PHP_EOL;
}
elseif (isset($loginp))
{
	echo "	<h2 id='title' style='font-size:5vmin' class='light'>" . PROJECT_NAME . "</h2>" . PHP_EOL;
}


//specific to detail page
if (isset($detailp))
{
	echo "<div class='feedback'>" . PHP_EOL;
		include "feedback.php";
	echo "</div>" . PHP_EOL;
}


//specific to index page
if (isset($indexp))
{
	echo "<div class='right'>" . PHP_EOL;

	listSelect();

	echo "</div>" . PHP_EOL;
}

//closing the div id="head"
echo "</div>" . PHP_EOL;

?>




<!-- THANKS INTERNET EXPLORER!!!!! -->
<!-- IE doesn't allow having multiple functions in a single script-->
<script>

function submitForm(elemId){
	document.getElementById(elemId).submit();
}

</script>

<script>

//for a collapsing element
function show(elementId, listElem)
{
	document.getElementById(elementId).style.display = 'block';

	if (listElem!==false)
	{
		//put make it seen which ones are open
		document.getElementById(listElem).style.backgroundColor = "#ff7900";
	}

	return false;
}

</script>

<script>

function collapse(elementId, listElem)
{
	document.getElementById(elementId).style.display = 'none';

	if (listElem!==false)
	{
		//put make it seen which ones are open
		document.getElementById(listElem).style.backgroundColor = "#ffb400";
	}

	return false;
}

</script>

<script>

function toggle(elementId, listElem)
{
	var elem = document.getElementById(elementId);
	var colChange = document.getElementById(listElem);

	if (elem.style.display=='none')
	{
		elem.style.display = 'block';

		colChange.style.backgroundColor = '#ff7900';
	}
	else
	{
		elem.style.display = 'none';

		colChange.style.backgroundColor = '#ffb400';
	}
}

</script>

<script>


//for the sidenav
function openNav()
{
	document.getElementById("sidenav").style.width = "250px";
}

</script>

<script>

function closeNav()
{
	document.getElementById("sidenav").style.width = "0";
}

</script>

<script>

//to show an element when the selectchanges 
//can't use rest parameters in Internet explorer................
function changedSelect(changedElement, showElement, value1, value2 = null){

	var elem = document.getElementById(changedElement);

	document.getElementById(showElement).style.display="none";

	if (value1===true || elem.value==value1 || elem.value==value2)
	{
		document.getElementById(showElement).style.display="block";
	}
}

</script>

<script>

/**
 * detect IE
 * returns version of IE or false, if browser is not Internet Explorer
 * THANKS TO codepen.io/anon/pen/bLNREa for this function
 */
function detectIE() {
  var ua = window.navigator.userAgent;

  // Test values; Uncomment to check result 

  // IE 10
  // ua = 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)';

  // IE 11
  // ua = 'Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko';

  // Edge 12 (Spartan)
  // ua = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36 Edge/12.0';

  // Edge 13
  // ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Safari/537.36 Edge/13.10586';

  var msie = ua.indexOf("MSIE ");
  if (msie > 0) {
    // IE 10 or older => return version number
    return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)), 10);
  }

  var trident = ua.indexOf("Trident/");
  if (trident > 0) {
    // IE 11 => return version number
    var rv = ua.indexOf("rv:");
    return parseInt(ua.substring(rv + 3, ua.indexOf(".", rv)), 10);
  }

  var edge = ua.indexOf("Edge/");
  if (edge > 0) {
    // Edge (IE 12+) => return version number
    return parseInt(ua.substring(edge + 5, ua.indexOf(".", edge)), 10);
  }

  // other browser
  return false;
}



</script>



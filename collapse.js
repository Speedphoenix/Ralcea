
// THANKS INTERNET EXPLORER!!!!!
//IE doesn't allow having multiple functions in a single script
//as such this file is not used (would have to make a different file for each func....
//you can find all of these at the bottom of header_generic.php


//submits a form. used in some onChanges for select menu
function submitForm(elemId){
	document.getElementById(elemId).submit();
}


//to show a collapsing element
function show(elementId, listElem)
{
	document.getElementById(elementId).style.display = 'block';

	if (listElem!==false)
	{
		//make it seen which ones are open
		document.getElementById(listElem).style.backgroundColor = "#ff7900";
	}

	return false;
}

//to hide a collapsing element
function collapse(elementId, listElem)
{
	document.getElementById(elementId).style.display = 'none';

	if (listElem!==false)
	{
		//make it seen which ones are closed
		document.getElementById(listElem).style.backgroundColor = "#ffb400";
	}

	return false;
}

//to toggle a collapsing element
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


//for the sidenav (open it)
function openNav()
{
	document.getElementById("sidenav").style.width = "250px";
}

//for the sidenav (close it)
function closeNav()
{
	document.getElementById("sidenav").style.width = "0";
}


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




<?php
if (isset($indexp))
{
echo "	<div id='footer'>
		<p>" . I18('Dernier changement') . " " . LAST_CHANGE . "</p>
	</div>";
}

//what to change on IE as an admin, on index.php
if (isset($indexp) AND $canChange)
{
?>

<script>

if (detectIE()!==false)
{
	document.getElementById("userPos").style.marginLeft = "43px";
}

</script>

<?php
}


//what to change on IE on detail.php
if (isset ($detailp))
{
?>

<script>
var IEVersion = detectIE();

if (IEVersion!==false)
{
	var element = document.getElementById("main");

	element.style.marginLeft = "250px";
	document.getElementById("instructDisp").style.width = "calc(99% - 250px)";

	if (IEVersion<11) //doesn't support viewport percentage lengths
	{
		document.getElementById("title").style.fontSize = "";
	}

//	document.getElementById("IEShaming").style.display = "block";
}
</script>

<?php
}
?>

</body>
</html>

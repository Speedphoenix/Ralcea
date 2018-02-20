<?php

if (isset($_POST['applicable']))
{
	$applicable = $_POST['applicable'];

	$ITname = withoutTag($_GET['choix']);

	$rating = 0;

	if (isset($_POST['name']))
		$name = $_POST['name'];
	else
		$name = '';

	if (isset($_POST['email']))
		$email = $_POST['email'];
	else
		$email = '';

	if (isset($_POST['textFeedback']))
		$probDetail = $_POST['textFeedback'];
	else
		$probDetail = '';


	if ($_POST['applicable']=='notApp')
	{
		$mailmsg = "$name, $email, a signalé, pour l'IT: '$ITname'." . PHP_EOL . "detail: $probDetail";

		if (mail(MAIL_FEED, "Une it a été marquée comme non applicable", $mailmsg))
		{
			//ça a marché
		}
		else
		{
			echo "<h3 style='color:red'>" . I18("L'envoi de l'email a échoué") . "</h3>";
		}
	}
	else
	{
		if (isset($_POST['rating']))
			$rating = $_POST['rating'];
		else
			$rating = '';
	}

	if ($file = fopen(FEEDBACKFILE, "a"))
	{
		fputcsv($file, array($applicable, $name, $email, $rating, $probDetail));
	}
	else
	{
		echo "<h2 style='color:red'>" . I18("L'enregistrement du feedback a échoué. (problème de fichier)") . "</h2>";
	}
}



//th IT exists
if ($filepath!==false)
{
	$choix = $_GET['choix'];

?>

<form method="post" action="">

<input type="radio" name="applicable" value="false" id="notApp"
onclick="show('notAppContent', false);show('forBoth', false);collapse('isAppContent', false);">
<label for="notApp"><?=I18("Cette IT n'est pas/plus applicable")?></label>

<input type="radio" name="applicable" value="true" id="isApp"
onclick="show('isAppContent', false);show('forBoth', false);collapse('notAppContent', false);">
<label for="isApp"><?=I18("Cette IT est applicable")?></label>



<div id="isAppContent" style="display:none">

	<p><?=I18("À quel point cette IT à-t-elle résolu le problème");?></p>

	<input type="hidden" name="choix" value="<?php echo $choix;?>">

	<fieldset class="starContain">

<?php
	//the stars.
	//when clicking on a star te rest of the form shows up

	//somewhat useless
	echo "<input type='radio' class='hiddenRadio' name='rating' value='0'>";

	for ($i=1;$i<=5;$i++)
	{
		echo "<input type='radio' name='rating' value='$i' id='radio$i' onclick='show(\"submitFeedback\", false);show(\"textFeedback\", false);'>
		<label for='radio$i'>&#9733;</label>" . PHP_EOL;
	}
?>

	</fieldset>

</div>


<div id="notAppContent" style="display:none">

<!--	<p><?=I18("Expliquez pourquoi")?></p>-->

</div>


<div id="forBoth" style="display:none">

	<textarea name="textFeedback" placeholder="<?=I18('Detaillez')?>" id="textFeedback" rows="4" cols="70" warp="hard"></textarea>

	<br>
	<input type="text" name="name" placeholder="<?=I18("Nom")?>">
	<br>
	<input type="email" name="email" placeholder="email">
	<br>
	<input type="submit" id="submitFeedback" value="<?php echo I18("Valider");?>">

</div>

</form>

<?php
} //endif

?>


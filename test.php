<?php

include("captcha_lib.php");

$form_valid = false;

?><h1>My Website</h1>

<form method="POST">

<p>Input: <input type="text" name="input"/></p>

<p>Captcha:<br>
<?php
if (captcha_check_and_show()) {
	$form_valid = true;
	echo "okay";
}
?>
</p>

<?php
if ($form_valid) {
	echo "Form is valid. Input: ".$_POST['input']."\n";
}
?>

<p><input type="submit" value="okay"/></p>
</form>

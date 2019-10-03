<?php

include("captcha_lib.php");

$form_valid = false;

?><h1>My Website</h1>

<form method="POST">

<p>Input: <input type="text" name="input"/></p>

<?php
echo "<p>\n";
session_start();
if (!isset($_SESSION['key'])) {
	$_SESSION['key'] = 5;
}
else {
	$_SESSION['key']++;
}
echo "session id: ".session_id()."<br/>";
echo "session data: "; print_r($_SESSION);
echo "</p>\n";

if (captcha_check()) {
	$form_valid = true;
	echo "<p>okay</p>";
} else {
	echo "<p>\n";
	echo "session id: ".session_id()."<br/>";
	echo "session data: "; print_r($_SESSION);
	echo "</p>\n";

	echo "<p>\n";
	echo captcha_show();
	echo "</p>\n";
}

echo "<p>\n";
if (!isset($_SESSION['key2'])) {
	$_SESSION['key2'] = 1;
}
else {
	$_SESSION['key2']++;
}
echo "session id: ".session_id()."<br/>";
echo "session data: "; print_r($_SESSION);
echo "</p>\n";
?>

<?php
if ($form_valid) {
	echo "Form is valid. Input: ".$_POST['input']."\n";
}
?>

<p><input type="submit" value="okay"/></p>
</form>

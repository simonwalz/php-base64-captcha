<?php

/**
 * Create captcha image
 */
function captcha_create_image() {
	global $_SESSION;

	$secret = (string)rand(10000, 99999);
	$_SESSION["captcha"] = $secret;

	$width = 50;
	$height = 22;
	$image = imagecreatetruecolor($width, $height);
	$color_text = imagecolorallocate($image, 120, 120, 120);
	$color_background = imagecolorallocate($image, 255, 255, 255);

	imagefill($image, 0, 0, $color_background);
	// Draw text: image, font, x, y, text, color
	imagestring($image, 5, 3, 3, $secret, $color_text);

	// Draw lines: image, x1, y1, x2, y2, color
	for ($i=-1*$height; $i<=$width; $i+=6) {
		imageline(
			$image,
			$i,
			0,
			$height+$i,
			$height,
			$color_text);
	}
	for ($i=0; $i<=$width+$height; $i+=12) {
		imageline($image, 0, $i, $i, 0, $color_text);
	}

	// create picture:
	ob_start();
	imagepng($image, null, 0, PNG_NO_FILTER);
	$image_base64 = 'data:image/png;base64,'.
			base64_encode(ob_get_contents());
	imagedestroy($image);
	ob_end_clean();

	return $image_base64;
}

/**
 * Captcha check code
 */
function captcha_check() {
	global $_POST, $_SESSION;

	$old_session_id = captcha_session_backup();
	$session_id = captcha_start_session();

	// check captcha
	if (isset($_SESSION['captcha'])) {
		if (hash_equals($_POST["captcha"],
				$_SESSION["captcha"])) {
			session_destroy();
			captcha_session_restore($old_session_id);

			return true;
		} else {
			//echo "captcha wrong. try again:<br/>\n";
			unset($_SESSION['captcha']);
		}
	}
	session_write_close();
	captcha_session_restore($old_session_id);
	return false;
}

/**
 * Start php session
 */
function captcha_start_session() {
	global $_POST;

	// start new php session:
	if (isset($_POST['session'])) {
		session_id($_POST['session']);
	}
	else {
		session_id(session_create_id());
	}
	// TODO: set expire
	session_start(array(
		"use_cookies" => 0,
		"use_only_cookies" => 0,
		"use_trans_sid" => 0,
		"use_strict_mode" => 0,
	));

	$session_id = session_id();
	return $session_id;
}

/**
 * Save old php session
 */
function captcha_session_backup() {
	// backup old php session:
	$old_session_id = null;
	if (session_status() === PHP_SESSION_ACTIVE) {
		$old_session_id = session_id();
		session_write_close();
	}

	return $old_session_id;
}

/**
 * Restore old php session
 */
function captcha_session_restore($old_session_id) {
	if ($old_session_id) {
		// echo "restore: ".$old_session_id."<br/>";
		session_id($old_session_id);
		session_start(array(
			"use_cookies" => 0,
			"use_only_cookies" => 0,
			"use_trans_sid" => 0,
			"use_strict_mode" => 0,
		));
	}
}

/**
 * Display HTML code
 */
function captcha_show() {
	$old_session_id = captcha_session_backup();
	$session_id = captcha_start_session();

	$image_base64 = captcha_create_image();

	session_write_close();

	captcha_session_restore($old_session_id);

	return '<input type="hidden" name="session" value="'.$session_id.'"/>'.
		'<img src="'.$image_base64.'" '.
			'style="vertical-align: middle;"/>'.
		'<input type="text" name="captcha" maxlength="5" size="5"/>';
}

/**
 * Create and check captcha
 */
function captcha_check_and_show() {

	if (captcha_check()) {
		return true;
	}

	echo captcha_show();

	return false;
}

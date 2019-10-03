# simple php captcha library using base64 images and php sessions

This simple captcha library uses base64 images to display the capture and
php sessions without cookies to store the secrets.
Thus only one script is needed.

## Usage: show image in place

```php
include("captcha_lib.php");

if (captcha_check_and_display()) {
	echo "captcha okay";
}
```

## Usage: seperate check and display

```php
include("captcha_lib.php");

if (captcha_check()) {
	// ... handle data
} else {
	// ... display form

	echo captcha_show();
}
```

<?php

require_once('lib/recaptcha/recaptchalib.php');

// Get a key from https://www.google.com/recaptcha/admin/create
$publickey = "your public key here";
$privatekey="your private key here";

# the response from reCAPTCHA
$resp = null;

# was there a reCAPTCHA response?
if ($_POST["recaptcha_response_field"]) {
	$resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);

	if ($resp->is_valid) {
		echo "success";
	} else {
		echo "failure";
	}
}

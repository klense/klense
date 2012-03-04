<?php

	if(!isset($GLOB)) die();

	if(Session::isAuthenticated()) {
		// Vai alla pagina principale
		header("location: " . currentFullBaseUrl());
		exit();
	}

	require_once('includes/libs/recaptcha/recaptchalib.php');


	$smarty->assign('pageTitle', "klense");
	$smarty->assign('error', '');

	$recaptcha_error = '';

	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		// Controlla i campi
		$resp = recaptcha_check_answer ($cfg['recaptcha_privatekey'],
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
  
		if ($resp->is_valid) {

			if($_POST['password'] == $_POST['password_c']) {
				try {
					$user = new User($GLOB['db']);
					$user->setUsername($_POST['username']);
					$user->setEmail($_POST['email']);
					$user->setPassword($_POST['password']);
					$user->setActivated(false);
					$user->setNewActivationCode();
					$user->setRegistrationTime(new DateTime());
					$user->setEnabled(true);
					$user->setBirthDate(new DateTime($_POST['birth_date']));
					$user->setTimezone(new DateTimeZone($_POST['timezone']));

					$user->save();

					if(sendConfirmMail($user)) {

						$smarty->assign('email', htmles($user->getEmail()));

						$smarty->display('register.mailsent.tpl');

					} else {
						// Elimina utente e segnala il problema
						$user->delete();
						$smarty->assign('error', htmles("Non è stato possibile inviare la email di conferma. Ripetere la registrazione."));
					}
				} catch (Exception $e) {
					$smarty->assign('error', htmles($e->getMessage()));
				}
			} else {
				$smarty->assign('error', htmles("Le password non corrispondono."));
			}

		} else {
			$smarty->assign('error', htmles("Verification code is wrong."));
			$recaptcha_error = $resp->error;
		}

	}

	$timezones = DateTimeZone::listIdentifiers();
	$smarty->assign('timezones', $timezones);

/*
	$user = new User($GLOB['db'], 1);
	echo $user->getBirthDate()->format('Y-m-d H:i:s');


	$usertimezone = "Europe/Rome";
	date_default_timezone_set($usertimezone);


	$date = new DateTime('2012-01-01 00:00:00', new DateTimeZone('UTC')); // Carico la data dal DB, dove è salvata in UTC
	echo $date->format('Y-m-d H:i:s'), '<br />';

	$tz = $date->getTimezone();
	echo $tz->getName();
	echo '<br />';

	$date->setTimezone(new DateTimeZone($usertimezone)); // Dico che il fuso orario in cui la data deve essere visualizzata è $usertimezone
	echo $date->format('Y-m-d H:i:s'), '<br />';

	$tz = $date->getTimezone();
	echo $tz->getName();
*/

	$smarty->assign('recaptcha', recaptcha_get_html($cfg['recaptcha_publickey'], $recaptcha_error));

	$smarty->display('register.tpl');

	function sendConfirmMail(User $user)
	{
		global $cfg;

		$subject = "klense: confirm your email address!";
		$message = "Hi ".$user->getUsername().".\r\nPlease confirm your klense account by clicking this link:\r\n" . currentFullBaseUrl() . 'activate/' . $user->getId() . '/' . $user->getActivationCode();
		$header = "From: {$cfg['service_mail_displayname']} <{$cfg['service_mail']}>";

		$message = str_replace("\n.", "\n..", $message);
		$message = wordwrap($message, 70);
		
		return mb_send_mail($user->getEmail(), $subject, $message, $header);
	}
?>
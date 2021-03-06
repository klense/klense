<?php

	/*	##### DEVELOPERS #####
	 *
	 *	Please do not apply your personal settings to
	 *	this file. Write them in config.override.php instead.
	 *
	 *	This file should contain ONLY default values.
	 *	Contents of this file will be visible from Version Control Systems.
	 *	config.override.php is PRIVATE and IGNORED by VCS.
	 */

	$cfg['dbhost'] = '127.0.0.1';
	$cfg['dbuser'] = 'root';
	$cfg['dbpass'] = 'root';
	$cfg['dbname'] = 'klense';
	$cfg['table_prefix'] = 'kl'; // Prefisso nome tabelle. Es. 'kl'. Underscore finale aggiunto automaticamente.

	$cfg['service_mail'] = 'klense@example.com';
	$cfg['service_mail_displayname'] = 'klense';

	$cfg['recaptcha_publickey'] = '';
	$cfg['recaptcha_privatekey'] = '';

	$cfg['max_upload_size'] = 8388608; // 8MB - Remember to change this value in .htaccess and in plupload too
	$cfg['upload_dir'] = 'content/uploads'; // WITHOUT final slash. E.g. 'content/uploads'
	$cfg['temp_upload_dir'] = 'content/uploads-temp'; // WITHOUT final slash. E.g. 'content/uploads-temp'. Used for temporary chunks.

	$cfg['path_start'] = 0; // Int. If app installed in http://example.org/test/klense/, then this parameter = 2. If installed in http://example.org/, it is = 0.
	$cfg['session_expire_time'] = 604800; // Tempo (in secondi) di inattività prima che la sessione scada. Se impostato a zero, la sessione scade alla chiusura del browser.

	$cfg['sidebar_top_ad'] = ''; // HTML/JS code for the ad at the top of the sidebar
	$cfg['before_footer_ad'] = ''; // HTML/JS code for the ad at the bottom of the sidebar

	$cfg['analytics_code'] = ''; // HTML/JS code for services like google analytics

	@include("config.override.php");
	
?>

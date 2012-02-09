<?php

	require_once('includes/libs/php-gettext/gettext.inc');

	$locales_dir = 'content/locales';
	$locale = 'it_IT'; // TODO
	$textdomain = "klense";

	if (empty($locale))
		$locale = 'en_US';

	// T_* functions are "fallbacks". To force the use of php-gettext, we use _* functions

	//putenv('LANGUAGE='.$locale);
	putenv('LANG='.$locale);
	//putenv('LC_ALL='.$locale);
	//putenv('LC_MESSAGES='.$locale);
	_setlocale(LC_ALL,$locale);
	//_setlocale(LC_CTYPE,$locale);
	//_setlocale(LC_MESSAGES, $locale);

	//bindtextdomain($textdomain, $locales_dir);
	_bindtextdomain($textdomain, $locales_dir);
	//if(function_exists('bind_textdomain_codeset')) // bind_textdomain_codeset is supported only in PHP 4.2.0+
		//bind_textdomain_codeset($textdomain, 'UTF-8');	
	_bind_textdomain_codeset($textdomain, 'UTF-8');
	//textdomain($textdomain);
	_textdomain($textdomain);

?>
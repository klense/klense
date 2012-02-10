<?php

	require_once('includes/libs/php-gettext/gettext.inc');

	$i18n_locales_dir = 'content/locales';
	$i18n_textdomain = "klense";

	if (!isset($GLOB['locale']) || empty($GLOB['locale']))
		$GLOB['locale'] = 'en_US';

	function apply_locale() {
		global $GLOB, $i18n_locales_dir, $i18n_textdomain;

		// T_* functions are "fallbacks". To force the use of php-gettext, we use _* functions

		//putenv('LANGUAGE='.$GLOB['locale']);
		putenv('LANG='.$GLOB['locale']);
		//putenv('LC_ALL='.$GLOB['locale']);
		//putenv('LC_MESSAGES='.$GLOB['locale']);
		_setlocale(LC_ALL,$GLOB['locale']);
		//_setlocale(LC_CTYPE,$GLOB['locale']);
		//_setlocale(LC_MESSAGES, $GLOB['locale']);

		//bindtextdomain($i18n_textdomain, $i18n_locales_dir);
		_bindtextdomain($i18n_textdomain, $i18n_locales_dir);
		//if(function_exists('bind_textdomain_codeset')) // bind_textdomain_codeset is supported only in PHP 4.2.0+
			//bind_textdomain_codeset($i18n_textdomain, 'UTF-8');	
		_bind_textdomain_codeset($i18n_textdomain, 'UTF-8');
		//textdomain($i18n_textdomain);
		_textdomain($i18n_textdomain);
	}

?>
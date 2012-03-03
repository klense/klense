<?php
	$start_execution_time = microtime(true); 
	require_once("includes/global.php");

	header('Content-Type: text/html;charset=UTF-8');

	Session::refreshSession();
	$smarty->assign('authenticated', Session::isAuthenticated());

	/* Parameters START*/
	$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$urlInfo = parse_url($url);
	$tmp_paths = explode('/',mb_substr($urlInfo['path'],1));
	$GLOB['params'] = array(); // Parametri per index.php
	for($i = $cfg['path_start']; $i < count($tmp_paths); $i++)
	{
		$GLOB['params'][$i - $cfg['path_start']] = $tmp_paths[$i];
	}
	/* Parameters END */

	$GLOB['base_url'] = '';
	for($i = 0; $i < $cfg['path_start']; $i++)
	{
		$GLOB['base_url'] .= '/' . $tmp_paths[$i];
	}

	$smarty->assign('base_url', htmles($GLOB['base_url'])); // /my/klense
	$smarty->assign('absolute_base_url', htmles(currentFullBaseUrl())); // http://myhost.com/my/klense/
	$smarty->assign('base_url_params', htmles($_SERVER['REQUEST_URI'])); // /my/klense/image/view/22

	$timezone = 'UTC';

	$GLOB['user'] = Session::getAuthenticatedUser(); // If authenticated, $GLOB['user'] is an instance of the authenticated user.
	if(Session::isAuthenticated()) {
		$smarty->assign('user', array('username' => htmles($GLOB['user']->getUsername())));

		$timezone = $GLOB['user']->getTimezone()->getName();

		$GLOB['locale'] = 'it_IT'; // ottenere da lingua utente TODO
	} else {
		$GLOB['locale'] = 'it_IT'; // ottenere da lingua browser HTTP_ACCEPT_LANGUAGE TODO
	}
	apply_locale();

	// Imposta il fuso orario dell'utente
	date_default_timezone_set($timezone);

	$smarty->assign('base_url_params', htmles($_SERVER['REQUEST_URI']));

	$smarty->assign('sidebar_top_ad', $cfg['sidebar_top_ad']);
	$smarty->assign('before_footer_ad', $cfg['before_footer_ad']);
	$smarty->assign('analytics_code', $cfg['analytics_code']);

	$smarty->assign('right_sidebar', true);


	switch($GLOB['params'][0]) {
		case 'logout':
			Session::resetSession();
			header("location: " . currentFullBaseUrl());
			break;
		case 'login':
			require 'includes/pages/login.php';
			break;
		case 'register':
			require 'includes/pages/register.php';
			break;
		case 'activate':
			require 'includes/pages/activate.php';
			break;
		case '':
			require 'includes/pages/home.php';
			break;
		case 'image':
			if(isset($GLOB['params'][1])) {
				if($GLOB['params'][1] == 'upload') {
					require 'includes/pages/image.upload.php';
				} else if($GLOB['params'][1] == 'upload_b') {
					require 'includes/pages/image.upload.backend.php';
				} else if($GLOB['params'][1] == 'view') {
					require 'includes/pages/image.view.php';
				} else if($GLOB['params'][1] == 'edit-overlay') {
					require 'includes/pages/image.edit.overlay.php';
				} else if($GLOB['params'][1] == 'get') {
					require 'includes/pages/image.get.php';
				} else pageNotFound();
			} else pageNotFound();
			break;
		case 'browse':
			if(isset($GLOB['params'][1])) {
				if($GLOB['params'][1] == 'toptags') {
					require 'includes/pages/browse.toptags.php';
				} else pageNotFound();
			} else {
				require 'includes/pages/browse.php';
			}
			break;
		case 'user':
			if(isset($GLOB['params'][1])) {
				require 'includes/pages/user.php';
			} else pageNotFound();
			break;
		default:
			pageNotFound();
	}
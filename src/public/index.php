<?php
	require_once("includes/global.php");
	require_once("includes/utils.php");
	require_once("includes/classes/Session.php");
	require_once("includes/classes/User.php");

	Session::refreshSession();

	header('Content-Type: text/html;charset=UTF-8');

	$smarty->assign('authenticated', Session::isAuthenticated());

	$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$urlInfo = parse_url($url);
	$tmp_paths = explode('/',mb_substr($urlInfo['path'],1));
	$GLOB['params'] = array(); // Parametri per index.php
	for($i = $cfg['path_start']; $i < count($tmp_paths); $i++)
	{
		$GLOB['params'][$i - $cfg['path_start']] = $tmp_paths[$i];
	}

	$GLOB['base_url'] = '';
	for($i = 0; $i < $cfg['path_start']; $i++)
	{
		$GLOB['base_url'] .= '/' . $tmp_paths[$i];
	}

	$smarty->assign('base_url', htmlspecialchars($GLOB['base_url'], ENT_QUOTES));
	$smarty->assign('absolute_base_url', htmlspecialchars(currentFullBaseUrl(), ENT_QUOTES));
	$smarty->assign('base_url_params', htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES));

	$timezone = 'UTC';

	$GLOB['user'] = Session::getAuthenticatedUser(); // If authenticated, $GLOB['user'] is an instance of the authenticated user.
	if(Session::isAuthenticated()) {
		$smarty->assign('user', array('username' => htmlspecialchars($GLOB['user']->getUsername())));

		$timezone = $GLOB['user']->getTimezone()->getName();
	}

	// Imposta il fuso orario dell'utente
	date_default_timezone_set($timezone);

	$smarty->assign('base_url_params', htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES));


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
				} else if($GLOB['params'][1] == 'view') {
					require 'includes/pages/image.view.php';
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
				//TODO
			} else pageNotFound();
			break;
		default:
			pageNotFound();
	}
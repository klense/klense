<?php

	if(!isset($GLOB)) die();

	require_once("includes/libs/phplivex/PHPLiveX.php");

	if(!isset($GLOB['params'][2])) pageNotFound();
	if($GLOB['params'][1] <= 0) pageNotFound();
	try {
		$usr = new User($GLOB['params'][1]);
	} catch (Exception $e) {
		pageNotFound();
	}
	if($usr->getUsername() != $GLOB['params'][2]) pageNotFound();

	$ajax = new PHPLiveX(array());
	$phplivex_init = $ajax->Run($GLOB['base_url'] . '/content/js/phplivex/phplivex.js', array(), true);

	// Prevent loading unnecessary stuff if the request is not from phplivex
	if(PHPLiveX::isAjaxRequest() == false)
	{

		$smarty->assign('phplivex_init', $phplivex_init);

		$smarty->assign('pageTitle', "klense");

		//$smarty->display('image.view.tpl');

	}



?>
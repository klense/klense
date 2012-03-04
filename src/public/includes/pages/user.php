<?php

	if(!isset($GLOB)) die();

	require_once("includes/libs/phplivex/PHPLiveX.php");

	if(!isset($GLOB['params'][2])) pageNotFound();
	if($GLOB['params'][1] <= 0) pageNotFound();
	try {
		$usr = new User(new UserDao($GLOB['dao']), $GLOB['params'][1]);
	} catch (Exception $e) {
		pageNotFound();
	}
	if($usr->getUsername() != $GLOB['params'][2]) pageNotFound();

	$ajax = new PHPLiveX(array());
	$phplivex_init = '';//$ajax->Run($GLOB['base_url'] . '/content/js/phplivex/phplivex.js', array(), true);

	// Prevent loading unnecessary stuff if the request is not from phplivex
	if(PHPLiveX::isAjaxRequest() == false)
	{

		$smarty->assign('phplivex_init', $phplivex_init);

		$smarty->assign('pageTitle', "klense");

		$smarty->assign('user_publicname', htmles($usr->getPublicName()));
		if($usr->getPublicName() != $usr->getUsername()) {
			$smarty->assign('user_username', htmles($usr->getUsername()));
		}

		$imgs = Image::getLastUploadedIds(-1, $usr->getId(), $GLOB['id']);
		$exit_images = array();
		$img_dao = new ImageDao($GLOB['dao'])
		foreach($imgs as $id) {
			$img = new Image($img_dao, $id);

			/* Build "Other sizes" array */
			$otherSizes = $img->getAllSizes();
			$maxSize = (isset($otherSizes['h_768'])) ? $otherSizes['h_768'] : $otherSizes['original'];

			$exit_images[] = array(  "filename" => htmles($img->getSafeFilename("sqr_64"))
									,"maxSize" => htmles($maxSize['link'])
									,"displayName" => htmles($img->getDisplayName())
									);
			unset($img);
		}

		$smarty->assign('thumbnails', $exit_images);

		$smarty->display('user.tpl');

	}



?>
<?php

	require_once("includes/classes/Image.php");

	if(!(isset($GLOB['params'][2]) && isset($GLOB['params'][3]))) pageNotFound();
	if($GLOB['params'][3] <= 0) pageNotFound();
	try {
		$img = new Image($GLOB['params'][3]);
	} catch (Exception $e) {
		pageNotFound();
	}
	$userid = User::getUserIdFromUsername($GLOB['params'][2]);
	if(!($userid > 0 && $img->getOwnerId() == $userid)) pageNotFound();

	$user = new User($userid);

	//$img->rebuildThumbnails(); // TODO cron

	$smarty->assign('pageTitle', htmlspecialchars($img->getDisplayName() . " | klense", ENT_QUOTES));

	$smarty->assign('image_displayName', htmlspecialchars($img->getDisplayName(), ENT_QUOTES));
	$smarty->assign('image_filename', htmlspecialchars($GLOB['base_url'] . '/' . $img->getSafeFilename('wh_size4'), ENT_QUOTES)); // Use full url for compatibility with external services (e.g. facebook)

	$smarty->assign('user_publicname', htmlspecialchars($user->getPublicName(), ENT_QUOTES));
	$smarty->assign('user_url', 'user/' . htmlspecialchars($user->getPublicName(), ENT_QUOTES));

	$exif = $img->getExif();
	$exif_disp = array();
	$myexif = array();

	if(isset($exif['IFD0']['Make']))
		$exif_disp['make'] = array('descr'=>'Produttore', 'val'=>htmlspecialchars($exif['IFD0']['Make'], ENT_QUOTES));
	if(isset($exif['IFD0']['Model']))
		$exif_disp['model'] = array('descr'=>'Modello', 'val'=>htmlspecialchars($exif['IFD0']['Model'], ENT_QUOTES));

	if(isset($exif['IFD0']['Make']) && isset($exif['IFD0']['Model']))
		$myexif['make_model'] = htmlspecialchars($exif['IFD0']['Make'] . ' ' . $exif['IFD0']['Model'], ENT_QUOTES);
	if(isset($exif['IFD0']['DateTime'])) {
		$dtime = new DateTime($exif['IFD0']['DateTime'], $user->getTimezone());
		$dtime->setTimezone(new DateTimeZone(date_default_timezone_get()));
		$myexif['shot_date'] = htmlspecialchars($dtime->format('d/m/Y'), ENT_QUOTES);
		$myexif['shot_time'] = htmlspecialchars($dtime->format('H:i'), ENT_QUOTES);
	}

	$smarty->assign('exif', $exif_disp);
	$smarty->assign('myexif', $myexif);

	$otherSizes = array(
					array(
						'descr'=>htmlspecialchars(_('Originale'), ENT_QUOTES),
						'link'=>htmlspecialchars($GLOB['base_url'] . '/' . $img->getSafeFilename(), ENT_QUOTES),
						'w'=>$img->getWidth(),
						'h'=>$img->getHeight()
					));
	$smarty->assign('otherSizes', $otherSizes);

	$smarty->assign('tags', $img->getTags());
	

	$smarty->display('image.view.tpl');


?>
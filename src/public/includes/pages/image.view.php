<?php

	if(!isset($GLOB)) die();

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

	$smarty->assign('pageTitle', htmles($img->getDisplayName() . " | klense"));

	$smarty->assign('image_displayName', htmles($img->getDisplayName()));
	$smarty->assign('image_filename', htmles($GLOB['base_url'] . '/' . $img->getSafeFilename('wh_size4'))); // Use full url for compatibility with external services (e.g. facebook)
	$smarty->assign('image_description', htmles($img->getDescription()));

	$smarty->assign('user_publicname', htmles($user->getPublicName()));
	$smarty->assign('user_url', 'user/' . htmles($user->getPublicName()));

	$exif = $img->getExif();
	$exif_disp = array();
	$myexif = array();

	if(isset($exif['IFD0']['Make']))
		$exif_disp['make'] = array('descr'=>'Produttore', 'val'=>htmles($exif['IFD0']['Make']));
	if(isset($exif['IFD0']['Model']))
		$exif_disp['model'] = array('descr'=>'Modello', 'val'=>htmles($exif['IFD0']['Model']));

	if(isset($exif['IFD0']['Make']) && isset($exif['IFD0']['Model']))
		$myexif['make_model'] = htmles($exif['IFD0']['Make'] . ' ' . $exif['IFD0']['Model']);
	if(isset($exif['IFD0']['DateTime'])) {
		$dtime = new DateTime($exif['IFD0']['DateTime'], $user->getTimezone());
		$dtime->setTimezone(new DateTimeZone(date_default_timezone_get()));
		$myexif['shot_date'] = htmles($dtime->format('d/m/Y'));
		$myexif['shot_time'] = htmles($dtime->format('H:i'));
	}

	$smarty->assign('exif', $exif_disp);
	$smarty->assign('myexif', $myexif);


	/* Build "Other sizes" array */
	if($img->getWidth() != 0 && $img->getHeight() != 0) { // Prevent DivisionByZero on malformed DB entries
		$otherSizes_prep = array(
						array(
							'file'=>$img->getFilename() . '--h_500',
							'descr'=>htmles(__('Medium')),
							'link'=>htmles($GLOB['base_url'] . '/' . $img->getSafeFilename('h_500')),
							'w'=>(int)(500 * $img->getWidth() / $img->getHeight()),
							'h'=>500
						),
						array(
							'file'=>$img->getFilename() . '--h_768',
							'descr'=>htmles(__('Medium')),
							'link'=>htmles($GLOB['base_url'] . '/' . $img->getSafeFilename('h_768')),
							'w'=>(int)(768 * $img->getWidth() / $img->getHeight()),
							'h'=>768
						),
						array(
							'file'=>$img->getFilename() . '--h_1024',
							'descr'=>htmles(__('Large')),
							'link'=>htmles($GLOB['base_url'] . '/' . $img->getSafeFilename('h_1024')),
							'w'=>(int)(1024 * $img->getWidth() / $img->getHeight()),
							'h'=>1024
						),
						array(
							'file'=>$img->getFilename(),
							'descr'=>htmles(__('Original')),
							'link'=>htmles($GLOB['base_url'] . '/' . $img->getSafeFilename()),
							'w'=>$img->getWidth(),
							'h'=>$img->getHeight()
						)
				);
		$otherSizes = array();
		foreach($otherSizes_prep as $size) {
			if(file_exists($size['file'])) {
				$otherSizes[] = array('descr'=>$size['descr'], 'link'=>$size['link'], 'w'=>$size['w'], 'h'=>$size['h']);
			}
		}
	}

	$smarty->assign('otherSizes', $otherSizes);


	/* Tags */
	$smarty->assign('tags', $img->getTags());
	

	$smarty->display('image.view.tpl');


?>
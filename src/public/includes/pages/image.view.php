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

	$owner = new User($userid);

	// TODO Only for admins
	if(isset($GLOB['params'][4]) && Session::isAuthenticated() && $GLOB['params'][4] == 'rebuild_thumbnails') {
		$img->rebuildThumbnails();
		$img->regenerateMetadata();
		$img->save();
	}

	$smarty->assign('pageTitle', htmles($img->getDisplayName() . " | klense"));

	$smarty->assign('image_id', $img->getId());
	$smarty->assign('image_displayName', htmles($img->getDisplayName()));
	$smarty->assign('image_filename', htmles($GLOB['base_url'] . '/' . $img->getSafeFilename('wh_size4'))); // Use full url for compatibility with external services (e.g. facebook)
	$smarty->assign('image_description', htmlEscapeAndLinkUrls($img->getDescription()));

	$smarty->assign('user_publicname', htmles($owner->getPublicName()));
	$smarty->assign('user_url', 'user/' . htmles($owner->getPublicName()));
	$smarty->assign('is_owner', (isset($_SESSION["uid"]) && $owner->getId() == $_SESSION["uid"]));


	/* Exif */
	$smarty->assign('hide_exif', $img->getHideExif());
	if(!$img->getHideExif()) {
		$exif = $img->getUserFriendlyExif(false); // Exif not escaped
		$exif_e = escape_array($exif); // Exif escaped
		$myexif = array(); // Elaborated exif data

		if(isset($exif['IFD0/Make']) && isset($exif['IFD0/Model']))
			$myexif['make_model'] = htmles($exif['IFD0/Make']['val'] . ' ' . $exif['IFD0/Model']['val']);
		if(isset($exif['IFD0/DateTime'])) {
			$dtime = new DateTime($exif['IFD0/DateTime']['val'], $owner->getTimezone());
			// TODO Use preferred locale format when printing date/times
			$myexif['shot_ownerdate'] = htmles($dtime->format('d/m/Y'));
			$myexif['shot_ownertime'] = htmles($dtime->format('H:i'));
			$dtime->setTimezone(new DateTimeZone(date_default_timezone_get()));
			$myexif['shot_userdatetime_iso'] = htmles($dtime->format('c'));
			$myexif['shot_userdatetime'] = htmles($dtime->format('d/m/Y H:i'));
		}

		$smarty->assign('exif_e', $exif_e);
		$smarty->assign('myexif', $myexif);
	}


	/* Build "Other sizes" array */
	$otherSizes = $img->getAllSizes();
	//$maxSize = $img->getBiggestSize(true, $otherSizes);
	$maxSize = (isset($otherSizes['h_768'])) ? $otherSizes['h_768'] : $otherSizes['original'];

	$smarty->assign('otherSizes', $otherSizes);
	$smarty->assign('maxSize', $maxSize['link']);


	/* Tags */
	$smarty->assign('tags', $img->getTags(true));
	$smarty->assign('tags_str', htmles($img->getTagsString()));

	/* Edit overlay form */
	$smarty->assign('edit_form', $smarty->fetch('image.edit.overlay.tpl'));

	/* Comments */
	$comment_number = 0;
	$comment_num_str = sprintf(_ngettext("%d comment", "%d comments", $comment_number), $comment_number);
	//echo $comment_num_str;

	$smarty->display('image.view.tpl');


?>
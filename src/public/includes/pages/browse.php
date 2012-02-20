<?php

	if(!isset($GLOB)) die();

	require_once("includes/classes/Image.php");

	// ini_set('exif.encode_unicode', 'UTF-8'); (SERVE?)

	//echo '<pre>', ini_get('upload_max_filesize'), "\n", ini_get('post_max_size'), "\n", ini_get('max_file_uploads'), "\n", ini_get('memory_limit'), "\n", ini_get('max_execution_time'), '</pre>';

	$smarty->assign('pageTitle', "klense");


	$imgs = Image::getLastUploadedIds(9);
	$exit_images = array();
	foreach($imgs as $id) {
		$img = new Image($id);
		$usr = $img->getOwner();
		$exit_images[] = array(  "filename" => htmles($img->getSafeFilename("wh_size2"))
								,"displayName" => htmles($img->getDisplayName())
								,"id" => $img->getId()
								,"user_publicname" => htmles($usr->getPublicName())
								,"user_url" => 'user/' . htmles($usr->getPublicName())
								,"imgurl" => "image/view/" . htmles($usr->getUsername()) . "/" . $img->getId()
								);
	}

	$smarty->assign('images', $exit_images);

	$smarty->display('browse.tpl');


?>
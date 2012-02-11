<?php

	if(!isset($GLOB)) die();

	if(!isset($GLOB['params'][2]) || $GLOB['params'][2] <= 0) pageNotFound();

	try {
		$img = new Image($GLOB['params'][2]);
	} catch (Exception $e) {
		pageNotFound();
	}


	try {
		$img->setDisplayName($_POST['displayName']);
		$img->setDescription($_POST['description']);
		$img->setTags(explode(',', $_POST['tags']));
		$img->setHideExif( (isset($_POST['hide_exif'])) ? true : false );

		$img->save();
	} catch (Exception $e) {
		pageNotFound();
	}

	echo "1";

?>
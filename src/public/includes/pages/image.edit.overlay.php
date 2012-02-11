<?php

	if(!isset($GLOB)) die();

	if(!isset($GLOB['params'][2]) || $GLOB['params'][2] <= 0) {
		echo htmles(__("Unknown error."));
		exit();
	}

	try {
		$img = new Image($GLOB['params'][2]);
	} catch (Exception $e) {
		echo htmles(__("Image not found."));
		exit();
	}

	if($GLOB['user']->getId() == $img->getOwnerId()) {

		try {
			$img->setDisplayName($_POST['displayName']);
			$img->setDescription($_POST['description']);
			$img->setTags(explode(',', $_POST['tags']));
			$img->setHideExif( (isset($_POST['hide_exif'])) ? true : false );

			$img->save();
		} catch (Exception $e) {
			echo htmles(__("The values are not valid."));
			exit();
		}

		echo "1";

	} else {

		echo htmles(__("You are not the owner of this image."));

	}

?>
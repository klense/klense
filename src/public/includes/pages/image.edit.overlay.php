<?php

	if(!isset($GLOB)) die();

	if(!isset($GLOB['params'][2]) || $GLOB['params'][2] <= 0) {
		echo htmles(__("Unknown error."));
		exit();
	}

	try {
		$img = new Image(new ImageDao($GLOB['dao']), $GLOB['params'][2]);
	} catch (Exception $e) {
		echo htmles(__("Image not found."));
		exit();
	}

	if($GLOB['user']->getId() == $img->getOwnerId()) {

		if($_POST['delete'] == "1") {

			// Delete
			try {
				Image::deleteFromId($img->getId(), new ImageDao($GLOB['dao']));
			} catch (Exception $e) {
				echo htmles(__("Error deleting image."));
				exit();
			}

			echo "2";

		} else {
		
			// Edit

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

		}

	} else {

		echo htmles(__("You are not the owner of this image."));

	}

?>
<?php

	if(!isset($GLOB)) die();

	require_once("includes/classes/Image.php");

	if(!Session::isAuthenticated()) {
		header("location: " . currentFullBaseUrl());
		exit();
	}

	// ini_set('exif.encode_unicode', 'UTF-8'); (SERVE?)

	//echo '<pre>', ini_get('upload_max_filesize'), "\n", ini_get('post_max_size'), "\n", ini_get('max_file_uploads'), "\n", ini_get('memory_limit'), "\n", ini_get('max_execution_time'), '</pre>';

	$smarty->assign('pageTitle', "klense");
	$smarty->assign('error', '');
	$smarty->assign('max_upload_size', $cfg['max_upload_size']);

	$recaptcha_error = '';

	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_FILES['file_upload'])) {
			$files = rearrange_files_array($_FILES['file_upload']);
			
			$upload_errors = '';
			$img_dao = new ImageDao($GLOB['dao']);
			foreach($files as $k=>$file) {
				try {
					$img = new Image($img_dao, 0, $file);
					$img->setDisplayName($file['name']);
					$img->setOwnerId($GLOB['user']->getId());
					$img->save();
					unset($img);
				} catch (Exception $e) {
					if($upload_errors != '') $upload_errors .= '<br />';
					$upload_errors .= htmles(__("Error uploading %s", $file['name']));
				}
			}

			$smarty->assign('error', $upload_errors);
		}
	}


	$smarty->display('image.upload.tpl');


?>
<?php

	if(!isset($GLOB)) die();

	require_once("includes/classes/Image.php");

	if(!isset($GLOB['params'][2])) pageNotFound();
	if(!isset($GLOB['params'][3]))
		$suffix = '';
	else {
		$suffix = str_replace(array('.', '/', '\\'), '', $GLOB['params'][3]);
	}

	try {
		$img = new Image($GLOB['db'], $GLOB['params'][2]);
	} catch (Exception $e) {
		pageNotFound();
	}

	// TODO Controlla permessi!

	//$userid = User::getUserIdFromUsername($GLOB['params'][2], $GLOB['db']);
	//if(!($userid > 0 && $img->getOwnerId() == $userid)) pageNotFound();

	//$user = new User($GLOB['db'], $userid);

	$fullPath = $img->getFilename();
	if($suffix != '') $fullPath .= '--' . $suffix;


	// Must be fresh start
	if( headers_sent() )
		die('Headers Sent');

	// Required for some browsers
	if(ini_get('zlib.output_compression'))
	ini_set('zlib.output_compression', 'Off');

	// File Exists?
	if( file_exists($fullPath) ){

		$fsize = filesize($fullPath); 

		// Determine Content Type (MIME)
		$ctype = $img->getMimeType();

		/*
		switch ($ext) {
			case "pdf": $ctype="application/pdf"; break;
			case "exe": $ctype="application/octet-stream"; break;
			case "zip": $ctype="application/zip"; break;
			case "doc": $ctype="application/msword"; break;
			case "xls": $ctype="application/vnd.ms-excel"; break;
			case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpeg":
			case "jpg": $ctype="image/jpg"; break;
			default: $ctype="application/force-download";
		}
		*/

		header("Pragma: public"); // required
		//header("Expires: 0");
		header("Cache-Control: max-age=315360000,public");
		//header("Cache-Control: private",false); // required for certain browsers
		header("Expires: " . date('r', time() + 315360000));
		header("Content-Type: $ctype");
		//header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: $fsize");
		ob_clean();
		flush();
		readfile( $fullPath );

		exit();

	} else die('File Not Found'); 


?>
<?php

	if(!isset($GLOB)) die();

	require_once("includes/libs/phplivex/PHPLiveX.php");

	if(!(isset($GLOB['params'][2]) && isset($GLOB['params'][3]))) pageNotFound();
	if($GLOB['params'][3] <= 0) pageNotFound();
	try {
		$img = new Image($GLOB['params'][3]);
	} catch (Exception $e) {
		pageNotFound();
	}
	$userid = User::getUserIdFromUsername($GLOB['params'][2]);
	if(!($userid > 0 && $img->getOwnerId() == $userid)) pageNotFound();

	$ajax = new PHPLiveX(array("addComment"));
	$phplivex_init = $ajax->Run($GLOB['base_url'] . '/content/js/phplivex/phplivex.js', array(), true);

	// Prevent loading unnecessary stuff if the request is not from phplivex
	if(PHPLiveX::isAjaxRequest() == false)
	{

		$smarty->assign('phplivex_init', $phplivex_init);

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
		$smarty->assign('user_url', 'user/' . $owner->getId() . '/' . htmles($owner->getUsername()));

		$is_owner = (isset($_SESSION["uid"]) && $owner->getId() == $_SESSION["uid"]);
		$smarty->assign('is_owner', $is_owner);


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
				// Use image owner timezone
				$myexif['shot_ownerdate'] = htmles($dtime->format('d/m/Y'));
				$myexif['shot_ownertime'] = htmles($dtime->format('H:i'));
				// Use viewer timezone
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

		/* Comments */
		$comments = $img->getComments();
		$plaincomments = array();
		foreach($comments as $comm) {
			$dtime = $comm->getDateTime(new DateTimeZone(date_default_timezone_get()));
			$comm_user = $comm->getUser();

			$plaincomments[] = array(
									'id' => $comm->getId(),
									'author_url' => 'user/' . $comm_user->getId() . '/' . $comm_user->getUsername(),
									'author_name' => $comm_user->getPublicName(),
									'datetime' => htmles($dtime->format('d/m/Y H:i')),
									'datetime_iso' => htmles($dtime->format('c')),
									'content' => htmlEscapeAndLinkUrls($comm->getContent())
								);
		}
		$comment_number = count($plaincomments);
		$comment_num_str = sprintf(_ngettext("%d comment", "%d comments", $comment_number), $comment_number);
		
		$smarty->assign('comments', $plaincomments);
		$smarty->assign('comments_count_str', $comment_num_str);

		if($is_owner) {
			$smarty->assign('image_views_sparklines', htmles(PageView::getImageViews(
																	new DateTime('-30 days', new DateTimeZone('UTC')),
																	new DateTime('now', new DateTimeZone('UTC')),
																	$img->getId(),
																	PageView::OutputMode_SimpleYXComma
																)));
		} else {
			PageView::addImageView($img->getId());
		}

		$smarty->display('image.view.tpl');

	}


	/* ajax functions */

	function addComment($content)
	{
		global $img, $smarty, $GLOB;
	
		try {
			$comm = new ImageComment();
			$comm->setContent($content);
			$comm->setUserId($GLOB['user']->getId());
			$comm->setImageId($img->getId());
			$comm->setDateTime(new DateTime('now'));
			$comm->save();
		} catch (Exception $e) {
			return "_error";
		}

		$dtime = $comm->getDateTime(new DateTimeZone(date_default_timezone_get()));
		$comm_user = $GLOB['user'];

		$comment = array(
								'id' => $comm->getId(),
								'author_url' => 'user/' . $comm_user->getId() . '/' . $comm_user->getUsername(),
								'author_name' => $comm_user->getPublicName(),
								'datetime' => htmles($dtime->format('d/m/Y H:i')),
								'datetime_iso' => htmles($dtime->format('c')),
								'content' => htmlEscapeAndLinkUrls($comm->getContent())
							);
		$smarty->assign('comment', $comment);
		
		return $smarty->fetch("image.view.comment.tpl");
	}


?>
<?php

	if(!isset($GLOB)) die();

	$smarty->assign('pageTitle', "klense");
	$smarty->assign('right_sidebar', false);
	

	$smarty->display('home.tpl');
?>
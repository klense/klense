<?php

	if(!isset($GLOB)) die();

	$smarty->assign('pageTitle', "klense - login");
	$smarty->assign('error', '');

	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		Session::resetSession();

		$userid = User::login($_POST['username'], $_POST['password']);
		if($userid > 0) {
			Session::authenticate($userid);
		} else {
			$smarty->assign('error', "Nome utente o password errati.");
		}
	}

	if(Session::isAuthenticated()) {
		// Vai alla pagina principale
		header("location: " . currentFullBaseUrl());
		exit();
	}

	$smarty->display('login.tpl');
?>
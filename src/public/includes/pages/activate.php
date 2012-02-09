<?php
	$smarty->assign('pageTitle', "klense");
	$smarty->assign('error', 'Error activating account.');

	if(isset($GLOB['params'][1]) && isset($GLOB['params'][2])) {
		try {
			$user = new User($GLOB['params'][1]);
			if($user->getActivated() == false && $user->getActivationCode() == $GLOB['params'][2]) {
				
				$user->setActivated(true);
				$user->save();

				// OK!
				$smarty->assign('error', '');
				$smarty->assign('username', htmlspecialchars($user->getUsername(), ENT_QUOTES));

			}
		} catch (Exception $e) {
			;
		}
	}

	$smarty->display('activate.tpl');
?>
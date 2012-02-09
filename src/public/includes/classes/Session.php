<?php

require_once("includes/classes/User.php");

class Session
{

	// Aggiorna la sessione, o la crea. È da chiamare all'inizio di ogni pagina caricata.
	// Serve, tra le varie cose, a aggiornare il nome della sessione e
	// ad aggiornare l'"expire time"
	public static function refreshSession()
	{
		global $cfg;

		session_name('klense');
		session_set_cookie_params($cfg['session_expire_time']);
		@session_start();

		$exp = time() + $cfg['session_expire_time'];
		if($cfg['session_expire_time'] == 0) {
			$exp = 0;
		}
		setcookie(session_name(), session_id(), $exp, '/');
	}

	public static function authenticate($userid)
	{
		if($userid > 0) {
			Session::refreshSession();
			$_SESSION["uid"] = $userid;
			return true;
		}
	}

	/**
	* Controlla se l'utente è già loggato.
	* 
	* @return false se l'utente non è loggato, true se è loggato.
	*/
	public static function isAuthenticated()
	{
		Session::refreshSession();

		if(isset($_SESSION["uid"]))
			return true;
		else
			return false;
	}

	// Logout
	public static function resetSession()
	{
		@session_destroy();
		@session_unset();

		Session::refreshSession();
	}

	public static function getAuthenticatedUser()
	{
		if(isset($_SESSION["uid"]))
			return new User($_SESSION["uid"]);
		else
			return false;
	}

}

?>
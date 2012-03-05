<?php

require_once("includes/classes/Dao.php");
require_once("includes/interfaces/DaoInterface.php");

class UserDao implements DaoInterface {

	private $dao;

	public function __construct(Dao $dao)
	{
		$this->dao = $dao;
	}

	public function getDao()
	{
		return $this->dao;
	}
	public function setDao(Dao $dao)
	{
		$this->dao = $dao;
	}
	
	public function getUserFromId($id)
	{
		$id = (int)$id;

		$query = "SELECT 
					username,
					password,
					email,
					activated,
					activation_code,
					registration_time,
					enabled,
					first_name,
					last_name,
					birth_date,
					sex,
					timezone
				FROM " . $this->dao->getPrefixedTable('users') . " WHERE id = $id";

		$result = mysql_query($query);

		if ($result !== false) {
			if(mysql_num_rows($result) > 0) {
				return mysql_fetch_assoc($result);
			} else return false;
		} else throw new Exception('Query error.', 10000002);
	}

	public function getAuthenticationDataFromUsername($username)
	{
		$id = (int)$id;

		$query = "SELECT 
					id,
					username,
					password,
					activated,
					enabled
				FROM " . $this->dao->getPrefixedTable('users') . " WHERE username = '" . mysql_real_escape_string($username) . "'";

		$result = mysql_query($query);

		if ($result !== false) {
			if(mysql_num_rows($result) > 0) {
				return mysql_fetch_assoc($result);
			} else return false;
		} else throw new Exception('Query error.', 10000002);
	}

	public function updateUserFromId($id, $username, $password, $email, $activated, $activation_code, DateTime $registration_time,
									$enabled, $first_name, $last_name, DateTime $birth_date, $sex, DateTimeZone $timezone)
	{
		$registration_time->setTimezone(new DateTimeZone('UTC'));
		$birth_date->setTimezone(new DateTimeZone('UTC'));

		$sql = "UPDATE " . $this->dao->getPrefixedTable('users') . " SET 
					username = '" 			. mysql_real_escape_string($username) . "',
					password = '" 			. mysql_real_escape_string($password) . "',
					email = '" 				. mysql_real_escape_string($email) . "',
					activated = "			. (int)$activated . ",
					activation_code = '"	. mysql_real_escape_string($activation_code) . "',
					registration_time = '"	. $registration_time->format('Y-m-d H:i:s') . "',
					enabled = "				. (int)$enabled . ",
					first_name = '"			. mysql_real_escape_string($first_name) . "',
					last_name = '"			. mysql_real_escape_string($last_name) . "',
					birth_date = '"			. $birth_date->format('Y-m-d 12:0:0') . "',
					sex = "					. (int)$sex . ",
					timezone = '"			. mysql_real_escape_string($timezone->getName()) . "'
				WHERE (id = " . (int)$id . ")";

		if(mysql_query($sql)) {
			return true;
		} else throw new Exception('Query error.', 10000002); 
	}

	public function insertUser($username, $password, $email, $activated, $activation_code, DateTime $registration_time,
									$enabled, $first_name, $last_name, DateTime $birth_date, $sex, DateTimeZone $timezone)
	{
		$registration_time->setTimezone(new DateTimeZone('UTC'));
		$birth_date->setTimezone(new DateTimeZone('UTC'));

		$sql = "INSERT INTO " . $this->dao->getPrefixedTable('users') . "
				(username, password, email, activated, activation_code, registration_time, enabled, first_name, last_name, birth_date, sex, timezone)
				VALUES (
					'" . mysql_real_escape_string($username) . "',
					'" . mysql_real_escape_string($password) . "',
					'" . mysql_real_escape_string($email) . "',
					"  . (int)$activated . ",
					'" . mysql_real_escape_string($activation_code) . "',
					'" . $registration_time->format('Y-m-d H:i:s') . "',
					"  . (int)$enabled . ",
					'" . mysql_real_escape_string($first_name) . "',
					'" . mysql_real_escape_string($last_name) . "',
					'" . $birth_date->format('Y-m-d 12:0:0') . "',
					"  . (int)$sex . ",
					'" . mysql_real_escape_string($timezone->getName()) . "'
				)";

		if(mysql_query($sql)) {

			// Get last inserted id
			return $this->getUserIdFromUsername($username);

		} else throw new Exception('Query error.', 10000002); 
	}

	public function getUserIdFromUsername($username)
	{
		$query = "SELECT 
					id
				FROM " . $this->dao->getPrefixedTable('users') . " WHERE username = '" . mysql_real_escape_string($username) . "'";

		$result = mysql_query($query);

		if ($result !== false) {
			if(mysql_num_rows($result) > 0) {
				$row = mysql_fetch_assoc($result);
				return (int)$row['id'];
			} else return -1;
		} else throw new Exception('Query error.', 10000002);
	}

	public function getUserIdFromEmail($email)
	{
		$query = "SELECT 
					id
				FROM " . $this->dao->getPrefixedTable('users') . " WHERE email = '" . mysql_real_escape_string($email) . "'";

		$result = mysql_query($query);

		if ($result !== false) {
			if(mysql_num_rows($result) > 0) {
				$row = mysql_fetch_assoc($result);
				return (int)$row['id'];
			} else return -1;
		} else throw new Exception('Query error.', 10000002);
	}

	public function deleteUserFromId($id)
	{
		$id = (int)$id;

		$query = "DELETE FROM " . $this->dao->getPrefixedTable('users') . "
				WHERE (id = $id)";
		if(mysql_query($query)) {
			/* Rimuovere tutte le altre associazioni TODO
				-images
				-friends
				-...
			*/
			return true;
		} else throw new Exception('Query error.', 10000002);
	}

}

?>

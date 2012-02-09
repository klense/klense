<?php

class User {

	const Sex_Undefined = -1;
	const Sex_Female = 0;
	const Sex_Male = 1;

	private $_id = 0;
	private $_username = '';
	private $_password = '';
	private $_email = '';
	private $_activated = false;
	private $_activationCode = '';
	private $_registrationTime = null;
	private $_enabled = true;
	private $_firstName = '';
	private $_lastName = '';
	private $_birthDate = null;
	private $_sex = -1;
	private $_timezone = null;

	public function __construct($id=0)
	{
		$id = (int)$id;
		$this->_id = $id;

		if($this->_id > 0) {
			// Carica tutti i valori nel caso in cui l'ID sia > 0
			global $cfg;
			
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
					FROM {$cfg['table_prefix']}_users WHERE id = {$this->_id}";

			$result = mysql_query($query);

			if ($result !== false) {
				if(mysql_num_rows($result) > 0) {
					$row = mysql_fetch_assoc($result);

					$this->_username = $row['username'];
					$this->_password = $row['password'];
					$this->_email = $row['email'];
					$this->_activated = (bool)$row['activated'];
					$this->_activationCode = $row['activation_code'];
					$this->_registrationTime = new DateTime($row['registration_time'], new DateTimeZone('UTC'));
					$this->_enabled = (bool)$row['enabled'];
					$this->_firstName = $row['first_name'];
					$this->_lastName = $row['last_name'];
					$this->_birthDate = new DateTime($row['birth_date'], new DateTimeZone('UTC'));
					$this->_sex = (int)$row['sex'];
					$this->_timezone = new DateTimeZone($row['timezone']);
				} else throw new Exception('ID does not exist.', 1000001);
			} else throw new Exception('Query error.', 10000002);

		} else {
			// Inizializza
			$this->_registrationTime = new DateTime('now', new DateTimeZone('UTC'));
			$this->_birthDate = new DateTime('2000-01-01', new DateTimeZone('UTC'));
			$this->_timezone = new DateTimeZone('UTC');
		}
	}
	
	function getId() { return $this->_id; }

	function getUsername() { return $this->_username; }
	function setUsername($value)
	{
		if(preg_match('/^[a-z\d_]{5,20}$/i', $value)) {
			// Controlla che non esistano altri utenti con questa email
			$existingId = self::getUserIdFromUsername($value);
			if($this->_id == 0 && $existingId > 0) throw new Exception('Username already exists.', 10000004);
			if($this->_id > 0 && $existingId > 0 && $existingId != $this->_id) throw new Exception('Username already exists.', 10000004);

			$this->_username = $value;
		} else throw new Exception('Invalid username.', 10000003);
	}
	
	function setPassword($value)
	{
		if(preg_match('/^.{6,}$/', $value)) {
			$this->_password = sha1($value);
		} else throw new Exception('Invalid password.', 10000003);
	}

	function getEmail() { return $this->_email; }
	function setEmail($value)
	{
		// Controlla che non esistano altri utenti con questa email
		$existingId = self::getUserIdFromEmail($value);
		if($this->_id == 0 && $existingId > 0) throw new Exception('Email already exists.', 10000004);
		if($this->_id > 0 && $existingId > 0 && $existingId != $this->_id) throw new Exception('Email already exists.', 10000004);

		$this->_email = mb_strtolower(str_replace(array(',',';','<','>'), '', $value));
	}
	
	function getActivated() { return $this->_activated; }
	function setActivated($value) { $this->_activated = (bool)$value; }

	function getActivationCode() { return $this->_activationCode; }
	function setNewActivationCode() { $this->_activationCode = md5(rand() . time() . 'act'); }
	
	function getRegistrationTime(DateTimeZone $timezone)
	{
		$d = $this->_registrationTime;
		$d->setTimezone($timezone);
		return $d;
	}
	function setRegistrationTime(DateTime $value) {
		if($value !== NULL) {
			$this->_registrationTime = $value;
			$this->_registrationTime->setTimezone(new DateTimeZone('UTC'));
		} else throw new Exception('Invalid registration time.', 10000003);
	}

	function getEnabled() { return $this->_enabled; }
	function setEnabled($value) { $this->_enabled = (bool)$value; }

	function getFirstName() { return $this->_firstName; }
	function setFirstName($value)
	{
		if(mb_strlen($value) <= 50) {
			$this->_firstName = $value;
		} else throw new Exception('Invalid first name.', 10000003);
	}

	function getLastName() { return $this->_lastName; }
	function setLastName($value)
	{
		if(mb_strlen($value) <= 50) {
			$this->_lastName = $value;
		} else throw new Exception('Invalid last name.', 10000003);
	}

	function getBirthDate(DateTimeZone $timezone) {
		$d = $this->_birthDate;
		$d->setTimezone($timezone);
		return $d;
	}
	function setBirthDate(DateTime $value) {
		if($value !== NULL) {
			$this->_birthDate = $value;
			$this->_birthDate->setTimezone(new DateTimeZone('UTC'));
		} else throw new Exception('Invalid birth date.', 10000003);
	}

	function getSex() { return $this->_sex; }
	function setSex($value)
	{
		if($value < -1 || $value > 1) $value = -1;
		$this->_sex = (int)$value; 
	}

	function getTimezone() { return $this->_timezone; }
	function setTimezone(DateTimeZone $value) { $this->_timezone = $value; }
	
	/*
	* Salva le modifiche a un record esistente (id > 0), o ne crea uno nuovo (id = 0).
	*
	* Restituisce l'id interessato dall'operazione, oppure "false" se l'operazione fallisce.
	*/	
	function save() {
		global $cfg;

		if($this->check_fields()) {

			if($this->_id > 0) {

				// Esegue un UPDATE

				$sql = "UPDATE {$cfg['table_prefix']}_users SET 
							username = '" 			. mysql_real_escape_string($this->_username) . "',
							password = '" 			. mysql_real_escape_string($this->_password) . "',
							email = '" 				. mysql_real_escape_string($this->_email) . "',
							activated = "			. (int)$this->_activated . ",
							activation_code = '"	. mysql_real_escape_string($this->_activationCode) . "',
							registration_time = '"	. $this->_registrationTime->format('Y-m-d H:i:s') . "',
							enabled = "				. (int)$this->_enabled . ",
							first_name = '"			. mysql_real_escape_string($this->_firstName) . "',
							last_name = '"			. mysql_real_escape_string($this->_lastName) . "',
							birth_date = '"			. $this->_birthDate->format('Y-m-d 12:0:0') . "',
							sex = "					. (int)$this->_sex . ",
							timezone = '"			. mysql_real_escape_string($this->_timezone->getName()) . "'
						WHERE (id = {$this->_id})";

				if(mysql_query($sql)) {
					return $this->_id;
				} else throw new Exception('Query error.', 10000002);

			} else {

				// Esegue un INSERT

				$sql = "INSERT INTO {$cfg['table_prefix']}_users
						(username, password, email, activated, activation_code, registration_time, enabled, first_name, last_name, birth_date, sex, timezone)
						VALUES (
							'" . mysql_real_escape_string($this->_username) . "',
							'" . mysql_real_escape_string($this->_password) . "',
							'" . mysql_real_escape_string($this->_email) . "',
							"  . (int)$this->_activated . ",
							'" . mysql_real_escape_string($this->_activationCode) . "',
							'" . $this->_registrationTime->format('Y-m-d H:i:s') . "',
							"  . (int)$this->_enabled . ",
							'" . mysql_real_escape_string($this->_firstName) . "',
							'" . mysql_real_escape_string($this->_lastName) . "',
							'" . $this->_birthDate->format('Y-m-d 12:0:0') . "',
							"  . (int)$this->_sex . ",
							'" . mysql_real_escape_string($this->_timezone->getName()) . "'
						)";

				if(mysql_query($sql)) {
					$this->_id = $this->getUserIdFromUsername($this->_username);
					return $this->_id;
				} else throw new Exception('Query error.', 10000002);

			}

		} else throw new Exception('Invalid values.');
	}

	// Controlla i campi obbligatori alla ricerca di valori non impostati
	private function check_fields()
	{
		if($this->_username == null) { // se username e' null o e' '' allora ...
			return false;
		}
		if($this->_password == null) { // se password e' null o e' '' allora ...
			return false;
		}
		if($this->_email == null) {
			return false;
		}
		if($this->_registrationTime == null) {
			return false;
		}
		if($this->_birthDate == null) {
			return false;
		}
		if($this->_timezone == null) {
			return false;
		}

		if($this->_id == 0) {
			if(!$this->check_fields_insert()) return false;
		} else {
			if(!$this->check_fields_update()) return false;
		}

		return true;
	}

	// Controlla i campi per fare l'UPDATE (es. username duplicati)
	private function check_fields_update()
	{
		$u = $this->getUserIdFromUsername($this->_username);
		if($u > 0 && $u != $this->_id) {
			return false;
		}
		$u = $this->getUserIdFromEmail($this->_email);
		if($u > 0 && $u != $this->_id) {
			return false;
		}

		return true;
	}

	// Controlla i campi per fare l'INSERT (es. username duplicati)
	private function check_fields_insert()
	{
		if($this->getUserIdFromUsername($this->_username) > 0) {
			return false;
		}
		if($this->getUserIdFromEmail($this->_email) > 0) {
			return false;
		}

		return true;
	}

	public function getPublicName()
	{
		return $this->getUsername(); // TODO
	}
	
	public static function login($username, $password)
	{
		global $cfg;

		$query = "SELECT 
					id,
					username,
					password,
					activated,
					enabled
				FROM {$cfg['table_prefix']}_users WHERE username = '" . mysql_real_escape_string($username) . "'";

		$result = mysql_query($query);

		if ($result !== false) {
			if(mysql_num_rows($result) > 0) {
				$row = mysql_fetch_assoc($result);
				if($row['activated'] && $row['enabled'] && $row['password'] == sha1($password)) {
					return (int)$row['id'];
				}
			} else return -1;
		} else throw new Exception('Query error.', 10000002);

	}

	public static function getUserIdFromUsername($username)
	{
		global $cfg;

		$query = "SELECT 
					id
				FROM {$cfg['table_prefix']}_users WHERE username = '" . mysql_real_escape_string($username) . "'";

		$result = mysql_query($query);

		if ($result !== false) {
			if(mysql_num_rows($result) > 0) {
				$row = mysql_fetch_assoc($result);
				return (int)$row['id'];
			} else return -1;
		} else throw new Exception('Query error.', 10000002);
	}

	public static function getUserIdFromEmail($email)
	{
		global $cfg;

		$query = "SELECT 
					id
				FROM {$cfg['table_prefix']}_users WHERE email = '" . mysql_real_escape_string($email) . "'";

		$result = mysql_query($query);

		if ($result !== false) {
			if(mysql_num_rows($result) > 0) {
				$row = mysql_fetch_assoc($result);
				return (int)$row['id'];
			} else return -1;
		} else throw new Exception('Query error.', 10000002);
	}

	/*
	* Elimina un utente esistente.
	*
	* Restituisce "true" se l'elemento viene eliminato, oppure "false" se l'operazione fallisce.
	*/
	public function delete()
	{
		// Elimina l'utente
		if($this->_id > 0) {

			global $cfg;
			
			$query = "DELETE FROM {$cfg['table_prefix']}_users
					WHERE (id = {$this->_id})";
			if(mysql_query($query)) {
				/* Rimuovere tutte le altre associazioni TODO
					-images
					-friends
					-...
				*/
				$this->_id = 0;
				return true;
			}
			throw new Exception('Query error.', 10000002);

		} else {
			throw new Exception('Cannot delete a new user.', 10000005);
		}
	}
	
}


?>
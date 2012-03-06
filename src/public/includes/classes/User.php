<?php

require_once("includes/classes/UserDao.php");

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

	private $mdao;

	public function __construct(UserDao $mdao, $id=0)
	{
		$this->mdao = $mdao;
		$id = (int)$id;
		$this->_id = $id;

		if($this->_id > 0) {
			// Carica tutti i valori nel caso in cui l'ID sia > 0
			
			$row = $this->mdao->getUserFromId($this->_id);
			if($row !== false) {
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
			$existingId = self::getUserIdFromUsername($value, $this->mdao);
			if($this->_id == 0 && $existingId > 0) throw new InvalidArgumentException('Username already exists.', 10000004);
			if($this->_id > 0 && $existingId > 0 && $existingId != $this->_id) throw new InvalidArgumentException('Username already exists.', 10000004);

			$this->_username = $value;
		} else throw new InvalidArgumentException('Invalid username.', 10000003);
	}
	
	function setPassword($value)
	{
		if(preg_match('/^.{6,}$/', $value)) {
			$this->_password = sha1($value);
		} else throw new InvalidArgumentException('Invalid password.', 10000003);
	}

	function getEmail() { return $this->_email; }
	function setEmail($value)
	{
		// Controlla che non esistano altri utenti con questa email
		$existingId = self::getUserIdFromEmail($value, $this->mdao);
		if($this->_id == 0 && $existingId > 0) throw new InvalidArgumentException('Email already exists.', 10000004);
		if($this->_id > 0 && $existingId > 0 && $existingId != $this->_id) throw new InvalidArgumentException('Email already exists.', 10000004);

		$this->_email = mb_strtolower(str_replace(array(',',';','<','>'), '', $value));
	}
	
	function getActivated() { return $this->_activated; }
	function setActivated($value) { $this->_activated = (bool)$value; }

	function getActivationCode() { return $this->_activationCode; }
	function setNewActivationCode() { $this->_activationCode = md5(rand() . time() . 'act'); }
	
	function getRegistrationTime(DateTimeZone $timezone)
	{
		$d = clone $this->_registrationTime;
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
		$d = clone $this->_birthDate;
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

		if($this->check_fields()) {

			if($this->_id > 0) {

				// Esegue un UPDATE

				$res = $this->mdao->updateUserFromId($this->_id,
														$this->_username,
														$this->_password,
														$this->_email,
														$this->_activated,
														$this->_activationCode,
														$this->_registrationTime,
														$this->_enabled,
														$this->_firstName,
														$this->_lastName,
														$this->_birthDate,
														$this->_sex,
														$this->_timezone
													);

				if($res) {
					return $this->_id;
				}

			} else {

				// Esegue un INSERT

				$this->_id = $this->mdao->insertUser($this->_username,
														$this->_password,
														$this->_email,
														$this->_activated,
														$this->_activationCode,
														$this->_registrationTime,
														$this->_enabled,
														$this->_firstName,
														$this->_lastName,
														$this->_birthDate,
														$this->_sex,
														$this->_timezone
													);
				return $this->_id;

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
		$u = $this->getUserIdFromUsername($this->_username, $this->mdao);
		if($u > 0 && $u != $this->_id) {
			return false;
		}
		$u = $this->getUserIdFromEmail($this->_email, $this->mdao);
		if($u > 0 && $u != $this->_id) {
			return false;
		}

		return true;
	}

	// Controlla i campi per fare l'INSERT (es. username duplicati)
	private function check_fields_insert()
	{
		if($this->getUserIdFromUsername($this->_username, $this->mdao) > 0) {
			return false;
		}
		if($this->getUserIdFromEmail($this->_email, $this->mdao) > 0) {
			return false;
		}

		return true;
	}

	public function getPublicName()
	{
		return $this->getUsername(); // TODO
	}
	
	public static function login($username, $password, UserDao $mdao)
	{
		$res = $mdao->getAuthenticationDataFromUsername($username);

		if ($res !== false) {
				if($res['activated'] && $res['enabled'] && $res['password'] == sha1($password)) {
					return (int)$res['id'];
				}
		} else return -1;

	}

	public static function getUserIdFromUsername($username, UserDao $mdao)
	{
		return $mdao->getUserIdFromUsername($username);
	}

	public static function getUserIdFromEmail($email, UserDao $mdao)
	{
		return $mdao->getUserIdFromEmail($email);
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

			$this->mdao->deleteUserFromId($this->_id);

			/* Rimuovere tutte le altre associazioni TODO
				-images
				-friends
				-...
			*/

			$this->_id = 0;
			return true;

		} else {
			throw new LogicException('Cannot delete a new user.', 10000005);
		}
	}
	
}


?>

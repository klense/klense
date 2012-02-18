<?php

class ImageComment {

	private $_id = 0;
	private $_user_id = 0;
	private $_image_id = 0;
	private $_datetime = null;
	private $_content = '';

	public function __construct($id=0, array $assocRowData=null)
	{
		if($assocRowData != null) {

			// Load data from the passed array
			$this->loadFromAssocRow($assocRowData);

		} else {

			$id = (int)$id;
			$this->_id = $id;

			if($this->_id > 0) {
				// Carica tutti i valori nel caso in cui l'ID sia > 0
				global $cfg;
				
				$query = "SELECT 
							id,
							user_id,
							image_id,
							datetime,
							content
						FROM {$cfg['table_prefix']}_images_comments WHERE id = {$this->_id}";

				$result = mysql_query($query);

				if ($result !== false) {
					if(mysql_num_rows($result) > 0) {
						$row = mysql_fetch_assoc($result);
						$this->loadFromAssocRow($row);
					} else throw new Exception('ID does not exist.', 1000001);
				} else throw new Exception('Query error.', 10000002);

			} else {
				// Inizializza
				$this->_datetime = new DateTime('now', new DateTimeZone('UTC'));
			}

		}
	}

	private function loadFromAssocRow($row) {
		$this->_id = (int)$row['id'];
		$this->_user_id = (int)$row['user_id'];
		$this->_image_id = (int)$row['image_id'];
		$this->_datetime = new DateTime($row['datetime'], new DateTimeZone('UTC'));
		$this->_content = $row['content'];
	}
	
	function getId() { return $this->_id; }

	function getUserId() { return $this->_user_id; }
	function setUserId($value) { $this->_user_id = (int)$value; }

	function getImageId() { return $this->_image_id; }
	function setImageId($value) {
		if($value > 0) {
			$this->_image_id = (int)$value;
		} else throw new Exception('Image id is not valid.', 10000009);
	}

	function getDateTime(DateTimeZone $timezone)
	{
		$d = $this->_datetime;
		$d->setTimezone($timezone);
		return $d;
	}
	function setDateTime(DateTime $value) {
		if($value !== NULL) {
			$this->_datetime = $value;
			$this->_datetime->setTimezone(new DateTimeZone('UTC'));
		} else throw new Exception('Invalid date/time.', 10000003);
	}
	
	function getContent() { return $this->_content; }
	function setContent($value)
	{
		if(mb_strlen($value) > 0 && mb_strlen($value) <= 1024) {
			$this->_content = $value;
		} else throw new Exception('Overflow or missing value.', 10000008);
	}
	
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

				$sql = "UPDATE {$cfg['table_prefix']}_images_comments SET 
							user_id = " 	. (int)$this->_user_id . ",
							image_id = "	. (int)$this->_image_id . ",
							datetime = '" 	. $this->_datetime->format('Y-m-d H:i:s') . "',
							content = '" 	. mysql_real_escape_string($this->_content) . "'
						WHERE (id = {$this->_id})";

				if(mysql_query($sql)) {
					return $this->_id;
				} else throw new Exception('Query error.', 10000002); 

			} else { 

				// Esegue un INSERT

				$sql = "INSERT INTO {$cfg['table_prefix']}_images_comments
						(user_id, image_id, datetime, content)
						VALUES (
							 " . (int)$this->_user_id . ",
							 " . (int)$this->_image_id . ",
							'" . $this->_datetime->format('Y-m-d H:i:s') . "',
							'" . mysql_real_escape_string($this->_content) . "'
						)";

				if(mysql_query($sql)) {

					// Get last inserted id
					$query = "SELECT id FROM {$cfg['table_prefix']}_images_comments WHERE 
								user_id = " 	. (int)$this->_user_id . ",
								image_id = " 	. (int)$this->_image_id . ",
								datetime = '" 	. $this->_datetime->format('Y-m-d H:i:s') . "'
							";

					$result = mysql_query($query);

					if ($result !== false) {
						if(mysql_num_rows($result) > 0) {
							$row = mysql_fetch_assoc($result);
							$this->_id = (int)$row['id'];
							return $this->_id;
						} else throw new Exception('Inserted id not found.', 10000002);;
					} else throw new Exception('Query error.', 10000002);

				} else throw new Exception('Query error.', 10000002);

			}

		} else throw new Exception('Invalid values.'); 
	}

	// Controlla i campi obbligatori alla ricerca di valori non impostati
	private function check_fields()
	{
		$this->setUserId($this->_image_id);
		$this->setImageId($this->_image_id);
		$this->setContent($this->_content);

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
		return true;
	}

	// Controlla i campi per fare l'INSERT (es. username duplicati)
	private function check_fields_insert()
	{
		return true;
	}

	public function getUser()
	{
		return new User($this->getUserId());
	}

	/*
	* Elimina un commento esistente.
	*
	* Restituisce "true" se l'elemento viene eliminato, oppure "false" se l'operazione fallisce.
	*/
	public function delete()
	{
		// Elimina il commento
		if($this->_id > 0) {

			global $cfg;
			
			$query = "DELETE FROM {$cfg['table_prefix']}_images_comments
					WHERE (id = {$this->_id})";
			if(mysql_query($query)) {
				$this->_id = 0;
				return true;
			}
			throw new Exception('Query error.', 10000002);

		} else {
			throw new Exception('Cannot delete a new comment.', 10000005);
		}
	}
	
}


?>

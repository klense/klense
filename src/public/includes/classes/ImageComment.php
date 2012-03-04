<?php

require_once("includes/classes/ImageCommentDao.php");

class ImageComment {

	private $_id = 0;
	private $_user_id = 0;
	private $_image_id = 0;
	private $_datetime = null;
	private $_content = '';

	private $mdao;

	public function __construct(ImageCommentDao $mdao, $id=0, array $assocRowData=null)
	{
		$this->mdao = $mdao;
		if($assocRowData != null) {

			// Load data from the passed array
			$this->loadFromAssocRow($assocRowData);

		} else {

			$id = (int)$id;
			$this->_id = $id;

			if($this->_id > 0) {

				$comment = $this->mdao->getImageCommentFromId($this->_id);
				if($comment !== false) {
					$this->loadFromAssocRow($comment);
				} else throw new Exception('ID does not exist.', 1000001);

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
		$d = clone $this->_datetime;
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

		if($this->check_fields()) {

			if($this->_id > 0) { 

				// Esegue un UPDATE

				$res = $this->mdao->updateImageCommentFromId($this->_id,
															$this->_user_id,
															$this->_image_id,
															$this->_datetime,
															$this->_content
														);

				if($res) {
					return $this->_id;
				}

			} else {

				// Esegue un INSERT

				$this->_id = $this->mdao->insertImageComment(	$this->_user_id,
																$this->_image_id,
																$this->_datetime,
																$this->_content
															);
				return $this->_id;
			}

		} else throw new Exception('Invalid values.'); 
	}

	// Controlla i campi obbligatori alla ricerca di valori non impostati
	private function check_fields()
	{
		$this->setUserId($this->_user_id);
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
		return new User(new UserDao($this->mdao->getDao()), $this->getUserId());
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

			if($this->mdao->deleteImageCommentFromId($this->_id)) {
				return true;
			}

		} else {
			throw new Exception('Cannot delete a new comment.', 10000005);
		}
	}
	
}


?>

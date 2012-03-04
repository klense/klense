<?php

class ImagePermission {

	private $_image_id = 0;
	private $_see_family = false;
	private $_see_friends = false;
	private $_see_link = false;
	private $_exif = array();
	private $__file = '';
	private $_upload_time = null;
	private $_tags = array();
	private $_width = 0;
	private $_height = 0;
	private $_mime = '';
	private $_hide_exif = false;

/*
	Browsing immagini:

	SELECT images.id from images
	INNER JOIN images_permissions ON images.id = images_permissions.image_id
	INNER JOIN users ON users.id = images.owner_id
	INNER JOIN user_friends ON ((user_friends.id1 = @my_uid AND user_friends.id2 = images.owner_id) OR (user_friends.id2 = @my_uid AND user_friends.id1 = images.owner_id))
	INNER JOIN user_family ON ((user_family.id1 = @my_uid AND user_family.id2 = images.owner_id) OR (user_family.id2 = @my_uid AND user_family.id1 = images.owner_id))
	WHERE (images.owner_id = @my_uid)
	OR (images_permissions.see_everyone = true)
	OR (user_friends.accepted = true AND (@my_uid = user_friends.id1 OR @my_uid = user_friends.id2))
	OR (user_family.accepted = true AND (@my_uid = user_family.id1 OR @my_uid = user_family.id2))

*/

	public function __construct($id=0, array $file=null)
	{
		$id = (int)$id;
		$this->_id = $id;

		if($this->_id > 0) {
			// Carica tutti i valori nel caso in cui l'ID sia > 0
			global $cfg;
			
			$query = "SELECT 
						display_name,
						file_name,
						owner_id,
						exif,
						upload_time,
						tags,
						width,
						height,
						mime,
						hide_exif
					FROM {$cfg['table_prefix']}_images WHERE id = {$this->_id}";

			$result = mysql_query($query);

			if ($result !== false) {
				if(mysql_num_rows($result) > 0) {
					$row = mysql_fetch_assoc($result);

					$this->_display_name = $row['display_name'];
					$this->_file_name = $row['file_name'];
					$this->_owner_id = (int)$row['owner_id'];
					$this->_exif = unserialize($row['exif']);
					$this->_upload_time = new DateTime($row['upload_time'], new DateTimeZone('UTC'));
					$this->_tags = explode(' ', $row['tags']);
					$this->_width = (int)$row['width'];
					$this->_height = (int)$row['height'];
					$this->_mime = $row['mime'];
					$this->_hide_exif = (bool)$row['hide_exif'];
				} else throw new Exception('ID does not exist.', 1010001);
			} else throw new Exception('Query error.', 10100002);

		} else {
			// Inizializza
			$this->__file = $file;
			if($this->upload_is_valid($file)) {
				$this->_mime = $file['type'];
				$image = new ImageManipulator();
				$image->load($file['tmp_name']);
				$this->_width = $image->getWidth();
				$this->_height = $image->getHeight();
				if($file['type'] == 'image/jpeg' || $file['type'] == 'image/tiff') {
					$this->_exif = exif_read_data( $file['tmp_name'], null, true );
				}
			} else throw new Exception('Upload not valid.', 10100007);

			$this->_upload_time = new DateTime('now', new DateTimeZone('UTC'));
		}
	}
	
	function getId() { return $this->_id; }

	function getDisplayName() { return $this->_display_name; }
	function setDisplayName($value) { $this->_display_name = $value; }

	function getFilename() { return $this->_file_name; }
	
	function getOwnerId() { return $this->_owner_id; }
	function setOwnerId($value) { $this->_owner_id = (int)$value; }

	function getExif() { return $this->_exif; }
	function setExif(array $value) { $this->_exif = $value; }

	function getUploadTime(DateTimeZone $timezone)
	{
		$d = $this->_upload_time;
		$d->setTimezone($timezone);
		return $d;
	}
	function setUploadTime(DateTime $value) {
		if($value !== NULL) {
			$this->_upload_time = $value;
			$this->_upload_time->setTimezone(new DateTimeZone('UTC'));
		} else throw new Exception('Invalid upload time.', 10000003);
	}

	function getTags()
	{
		$exit_vals = array();
		foreach($this->_tags as $key => $item) {
			$exit_vals[$key] = str_replace('_', ' ', $item);
		}
		return $exit_vals;
	}
	function setTags(array $value)
	{
		$vals = array_unique($value);
		$exit_vals = array();
		foreach($vals as $key => $item) {
			$exit_vals[$key] = mb_strtolower(str_replace(' ', '_', $item));
		}
		$this->_tags = array_unique($exit_vals);
	}

	function getWidth() { return $this->_width; }

	function getHeight() { return $this->_height; }

	function getMimeType() { return $this->_mime; }

	function getHideExif() { return $this->_hide_exif; }
	function setHideExif($value) { $this->_hide_exif = (bool)$value; }
	
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

				$sql = "UPDATE {$cfg['table_prefix']}_images SET 
							display_name = '" 		. mysql_real_escape_string($this->_display_name) . "',
							owner_id = " 			. (int)$this->_owner_id . ",
							exif = '"				. mysql_real_escape_string($this->_exif) . "',
							upload_time = '"		. $this->_upload_time->format('Y-m-d H:i:s') . "',
							tags = '"				. mysql_real_escape_string(implode(' ', $this->_tags)) . "',
							hide_exif = "			. (int)$this->_hide_exif . "
						WHERE (id = {$this->_id})";

				if(mysql_query($sql)) {
					return $this->_id;
				} else throw new Exception('Query error.', 10100002);

			} else {

				// Esegue un INSERT
				$user = $this->getOwner();
				$filename = $this->store($this->__file, $user->getUsername());
				if($filename !== false) {
					$this->_file_name = $filename;
					
					$sql = "INSERT INTO {$cfg['table_prefix']}_images
							(display_name, file_name, owner_id, exif, upload_time, tags, width, height, mime, hide_exif)
							VALUES (
								'" . mysql_real_escape_string($this->_display_name) . "',
								'" . mysql_real_escape_string($this->_file_name) . "',
								 " . (int)$this->_owner_id . ",
								'" . mysql_real_escape_string(serialize($this->_exif)) . "',
								'" . $this->_upload_time->format('Y-m-d H:i:s') . "',
								'" . mysql_real_escape_string(implode(' ', $this->_tags)) . "',
								 " . (int)$this->_width . ",
								 " . (int)$this->_height . ",
								'" . mysql_real_escape_string($this->_mime) . "',
								 " . (int)$this->_hide_exif . "
							)";

					if(mysql_query($sql)) {
						$this->_id = $this->getImageIdFromFilename($this->_file_name);
						return $this->_id;
					} else throw new Exception('Query error.', 10100002);

				} else throw new Exception('Error storing the file.', 10100006);

			}

		} else throw new Exception('Invalid values.');
	}

	// Controlla i campi obbligatori alla ricerca di valori non impostati
	private function check_fields()
	{
		if($this->_display_name == null) { // se display_name e' null o e' '' allora ...
			return false;
		}
		if($this->_owner_id == null) { // se e' null o e' 0 allora ...
			return false;
		}
		if(is_array($this->_exif) == false) {
			return false;
		}
		if(is_array($this->_tags) == false) {
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
		return true;
	}

	// Controlla i campi per fare l'INSERT (es. username duplicati)
	private function check_fields_insert()
	{
		if($this->upload_is_valid($this->__file) == false) {
			return false;
		}

		return true;
	}

	// Return an address that executes a php script
	public function getSafeFilename($suffix = '')
	{
		if($this->getId() > 0) {
			return 'image/get/' . $this->getId() . '/' . $suffix;
		} else return false;
	}
	
	// Store an uploaded (and ALREADY VALIDATED) image
	private static function store($file, $username)
	{
		global $cfg;


		// Make directories
		$i = 0;
		$incremental_name = '';
		$incremental_path = $cfg['upload_dir'];
		while($i < mb_strlen($username) && $i < 5) {
			$incremental_name .= $username[$i];
			$incremental_path .= "/$incremental_name";
			$i++;
		}
		$incremental_path .= "/$username";
		@mkdir($incremental_path, 0777, true);


		// Find unique name
		do {
			$out_server_filename = uniqid('i_');
		} while(file_exists($incremental_path . '/' . $out_server_filename));

		$end_filename = $incremental_path . '/' . $out_server_filename;

		// Move uploaded file
		if(move_uploaded_file($file['tmp_name'], $end_filename)) {

			// Generate thumbnails
			if(self::buildThumbnails($end_filename)) {
				return $end_filename;
			} else {
				//unlink TODO
			}
		}

		return false;
	}

	private static function buildThumbnails($filename)
	{
		// Delete old thumbnails
		$files = glob($filename . "--*");
		foreach($files as $file) {
			if(is_file($file)) {
				unlink($file);
			}
		}


		$image = new ImageManipulator();

		$image->load($filename);

		$originalWidth = $image->getWidth();
		$originalHeight = $image->getHeight();

		$SMALLER_IGNORE = 1;
		$SMALLER_FORCE = 2;
		$SMALLER_DUPLICATE = 3;

		$sizes = array(	array(	'width'  => 660,
								'height' => 470,
								'name'   => 'wh_size4',
								'smaller'=> $SMALLER_DUPLICATE
							),
						array(	'width'  => 200,
								'height' => 160,
								'name'   => 'wh_size2',
								'smaller'=> $SMALLER_DUPLICATE
							),
						array(	'width'  => 0,
								'height' => 500,
								'name'   => 'h_500',
								'smaller'=> $SMALLER_IGNORE
							),
						array(	'width'  => 0,
								'height' => 768,
								'name'   => 'h_768',
								'smaller'=> $SMALLER_IGNORE
							),
						array(	'width'  => 0,
								'height' => 1024,
								'name'   => 'h_1024',
								'smaller'=> $SMALLER_IGNORE
							)
						);


		foreach($sizes as $size) {
			$image->load($filename);

			$resizeWidth = false;
			if($originalWidth > 0 && $originalHeight > 0) {
				$resizeWidth = ($originalWidth >= $originalHeight);
			}
			if($size['width'] == 0) $resizeWidth = false;
			if($size['height'] == 0) $resizeWidth = true;


			if($resizeWidth) {
				if($originalWidth > $size['width']) {
					$image->resizeToWidth($size['width']);
					$image->save($filename . '--' . $size['name'], $image->getImageType());
				} elseif($originalWidth == $size['width']) {
					$image->save($filename . '--' . $size['name'], $image->getImageType());
				} elseif($size['smaller'] == $SMALLER_DUPLICATE) {
					$image->save($filename . '--' . $size['name'], $image->getImageType());
				} elseif($size['smaller'] == $SMALLER_FORCE) {
					$image->resizeToWidth($size['width']);
					$image->save($filename . '--' . $size['name'], $image->getImageType());
				}
			} else {
				if($originalHeight > $size['height']) {
					$image->resizeToHeight($size['height']);
					$image->save($filename . '--' . $size['name'], $image->getImageType());
				} elseif($originalHeight == $size['height']) {
					$image->save($filename . '--' . $size['name'], $image->getImageType());
				} elseif($size['smaller'] == $SMALLER_DUPLICATE) {
					$image->save($filename . '--' . $size['name'], $image->getImageType());
				} elseif($size['smaller'] == $SMALLER_FORCE) {
					$image->resizeToHeight($size['height']);
					$image->save($filename . '--' . $size['name'], $image->getImageType());
				}
			}

		}
/*

		if($originalHeight > 768) {
			$image->resizeToHeight(768);
			$image->save($filename . '--' . 'h_768', $image->getImageType());
		}
		if($originalHeight > 480) {
			$image->resizeToHeight(480);
			$image->save($filename . '--' . 'h_480', $image->getImageType());
		}
		if($originalHeight > 360) {
			$image->resizeToHeight(360);
			$image->save($filename . '--' . 'h_360', $image->getImageType());
		}
		if($originalHeight > 180) {
			$image->resizeToHeight(180);
			$image->save($filename . '--' . 'h_180', $image->getImageType());
		}
*/
		
		return true;
	}

	public function rebuildThumbnails()
	{
		if($this->getId() > 0) {
			return $this->buildThumbnails($this->getFilename());
		}
	}

	public static function upload_is_valid(array $file)
	{
		global $cfg, $GLOB;

		if($file['error'] == 0) {
			if(in_array($file['type'], $GLOB['supported_mimes'])) {
				if(is_uploaded_file($file['tmp_name'])) {
					if($file['size'] <= $cfg['max_upload_size']) {

						// Tutti i controlli sono stati superati.
						return true;

					}
				}
			}
		}

		return false;
	}

	private function getImageIdFromFilename($filename)
	{
		global $cfg;

		$query = "SELECT 
					id
				FROM {$cfg['table_prefix']}_images WHERE file_name = '" . mysql_real_escape_string($filename) . "'";

		$result = mysql_query($query);

		if ($result !== false) {
			if(mysql_num_rows($result) > 0) {
				$row = mysql_fetch_assoc($result);
				return (int)$row['id'];
			} else return -1;
		} else throw new Exception('Query error.', 10000002);
	}

	// Returns an array with the id of the last $num images uploaded
	public static function getLastUploadedIds($num)
	{
		global $cfg;
		$num = (int)$num;

		$query = "SELECT 
					id
				FROM {$cfg['table_prefix']}_images
				ORDER BY upload_time DESC
				LIMIT $num";

		$result = mysql_query($query);

		if ($result !== false) {
			$ret = array();
			while($row = mysql_fetch_assoc($result)) {
				$ret[] = (int)$row['id'];
			}
			return $ret;
		} else throw new Exception('Query error.', 10000002);
	}

}


?>

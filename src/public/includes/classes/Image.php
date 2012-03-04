<?php

require_once("includes/classes/User.php");
require_once("includes/classes/ImageManipulator.php");
require_once("includes/classes/ImageDao.php");

class Image {

	private $_id = 0;
	private $_display_name = '';
	private $_file_name = '';
	private $_owner_id = '';
	private $_exif = array();
	private $__file = '';
	private $_upload_time = null;
	private $_tags = array();
	private $_width = 0;
	private $_height = 0;
	private $_mime = '';
	private $_hide_exif = false;
	private $_description = '';

	private $mdao;

	private $standard_uploader = true;

	public function __construct(ImageDao $mdao, $id=0, array $file=null, $standard_uploader=true)
	{
		$this->mdao = $mdao;
		$id = (int)$id;
		$this->_id = $id;

		if($this->_id > 0) {
			// Carica tutti i valori nel caso in cui l'ID sia > 0
			$row = $this->mdao->getImageFromId($this->_id);
			if($row !== false) {
					$this->_display_name = $row['display_name'];
					$this->_file_name = $row['file_name'];
					$this->_owner_id = (int)$row['owner_id'];
					$this->_exif = unserialize($row['exif']);
					if($this->_exif === false) $this->_exif = array();
					$this->_upload_time = new DateTime($row['upload_time'], new DateTimeZone('UTC'));
					$this->_tags = (trim($row['tags']) == '') ? array() : explode(' ', $row['tags']);
					$this->_width = (int)$row['width'];
					$this->_height = (int)$row['height'];
					$this->_mime = $row['mime'];
					$this->_hide_exif = (bool)$row['hide_exif'];
					$this->_description = $row['description'];
			} else throw new Exception('ID does not exist.', 1000001);

		} else {
			// Inizializza
			$this->__file = $file;
			$this->standard_uploader = $standard_uploader;
			if($this->upload_is_valid($file, $this->standard_uploader)) {
				$this->generateMetadata($file['tmp_name'], $file['type']);
			} else throw new Exception('Upload not valid.', 10100007);

			$this->_upload_time = new DateTime('now', new DateTimeZone('UTC'));
		}
	}

	function getId() { return $this->_id; }

	function getDisplayName() { return $this->_display_name; }
	function setDisplayName($value)
	{
		if(mb_strlen($value) <= 128) {
			$this->_display_name = $value;
		} else throw new Exception('Overflow.', 10000008);
	}

	function getFilename() { return $this->_file_name; }
	
	function getOwnerId() { return $this->_owner_id; }
	function setOwnerId($value) { $this->_owner_id = (int)$value; }

	function getOwner() { return new User(new UserDao($this->mdao->getDao()), $this->_owner_id); }

	function getExif() { return $this->_exif; }
	function setExif(array $value) { $this->_exif = $value; }

	function getUploadTime(DateTimeZone $timezone)
	{
		$d = clone $this->_upload_time;
		$d->setTimezone($timezone);
		return $d;
	}
	function setUploadTime(DateTime $value) {
		if($value !== NULL) {
			$this->_upload_time = $value;
			$this->_upload_time->setTimezone(new DateTimeZone('UTC'));
		} else throw new Exception('Invalid upload time.', 10000003);
	}

	function getTags($escape = false)
	{
		$exit_vals = array();
		foreach($this->_tags as $key => $item) {
			$exit_vals[$key] = str_replace('_', ' ', $item);
			if($escape) $exit_vals[$key] = htmles($exit_vals[$key]);
		}
		return $exit_vals;
	}
	function getTagsString() { return implode(', ', $this->getTags()); }
	function setTags(array $value)
	{
		$vals = array_unique($value);
		$exit_vals = array();
		foreach($vals as $key => $item) {
			$exit_vals[$key] = mb_strtolower(str_replace(' ', '_', trim($item)));
		}
		
		$tags = array_unique($exit_vals);

		// Check max length for DB field
		if(mb_strlen(implode(' ', $tags)) <= 128) {
			$this->_tags = $tags;
		} else throw new Exception('Overflow.', 10000008);
	}

	function getWidth() { return $this->_width; }

	function getHeight() { return $this->_height; }

	function getMimeType() { return $this->_mime; }

	function getHideExif() { return $this->_hide_exif; }
	function setHideExif($value) { $this->_hide_exif = (bool)$value; }

	function getDescription() { return $this->_description; }
	function setDescription($value)
	{
		if(mb_strlen($value) <= 1024) {
			$this->_description = $value;
		} else throw new Exception('Overflow.', 10000008);
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

				$res = $this->mdao->updateImageFromId($this->_id,
														$this->_display_name,
														$this->_owner_id,
														serialize($this->_exif),
														$this->_upload_time,
														implode(' ', $this->_tags),
														$this->_width,
														$this->_height,
														$this->_mime,
														$this->_hide_exif,
														$this->_description
													);

				if($res) {
					return $this->_id;
				}

			} else {

				// Esegue un INSERT
				$user = $this->getOwner();
				$filename = $this->store($this->__file, $user->getUsername(), $this->standard_uploader);
				if($filename !== false) {

					$this->_file_name = $filename;

					$this->_id = $this->mdao->insertImage($this->_display_name,
															$this->_file_name,
															$this->_owner_id,
															serialize($this->_exif),
															$this->_upload_time,
															implode(' ', $this->_tags),
															$this->_width,
															$this->_height,
															$this->_mime,
															$this->_hide_exif,
															$this->_description
														);
					return $this->_id;
					
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
		if($this->upload_is_valid($this->__file, $this->standard_uploader) == false) {
			return false;
		}

		return true;
	}

	// Return an address that executes a php script
	public function getSafeFilename($suffix = '')
	{
		if($this->getId() > 0) {
			$ret = 'image/get/' . $this->getId() . '/' . $suffix;

			// Add image file name
			$ret .= '/klense-' . $this->getId() . '-' . $suffix;
			if($this->getMimeType() == 'image/jpeg') {
				$ret .= '.jpg';
			} elseif($this->getMimeType() == 'image/png') {
				$ret .= '.png';
			}

			return $ret;
		} else return false;
	}
	
	// Store an uploaded (and ALREADY VALIDATED) image
	private static function store($file, $username, $standard_uploader = true)
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
		if($standard_uploader) {
			$moved = move_uploaded_file($file['tmp_name'], $end_filename);
		} else {
			$moved = rename($file['tmp_name'], $end_filename);
		}

		if($moved) {

			// Generate thumbnails
			if(self::buildThumbnails($end_filename)) {
				return $end_filename;
			} else {
				// Remove original image and all thumbnails
				unlink($end_filename);
				$files = glob($end_filename . "--*");
				foreach($files as $file) {
					if(is_file($file)) {
						unlink($file);
					}
				}
				return false;
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

		if(!$image->load($filename)) return false;

		$originalWidth = $image->getWidth();
		$originalHeight = $image->getHeight();

		$SMALLER_IGNORE = 1;
		$SMALLER_FORCE = 2;
		$SMALLER_DUPLICATE = 3;

		$sizes = array(	array(	'width'  => 64,
								'height' => 64,
								'name'   => 'sqr_64',
								'smaller'=> null,
								'crop'   => true
							),
						array(	'width'  => 660,
								'height' => 470,
								'name'   => 'wh_size4',
								'smaller'=> $SMALLER_DUPLICATE,
								'crop'   => false
							),
						array(	'width'  => 200,
								'height' => 160,
								'name'   => 'wh_size2',
								'smaller'=> $SMALLER_DUPLICATE,
								'crop'   => false
							),
						array(	'width'  => 0,
								'height' => 500,
								'name'   => 'h_500',
								'smaller'=> $SMALLER_IGNORE,
								'crop'   => false
							),
						array(	'width'  => 0,
								'height' => 768,
								'name'   => 'h_768',
								'smaller'=> $SMALLER_IGNORE,
								'crop'   => false
							),
						array(	'width'  => 0,
								'height' => 1024,
								'name'   => 'h_1024',
								'smaller'=> $SMALLER_IGNORE,
								'crop'   => false
							)
						);

		foreach($sizes as $size) {

			if(!$image->load($filename)) return false;

			if($size['crop'] == true) { // Crop
				$image->resizeCrop($size['width'], $size['height']);
				$image->save($filename . '--' . $size['name'], $image->getImageType());
			} else { // Don't crop
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

		}
		return true;
	}

	public function rebuildThumbnails()
	{
		if($this->getId() > 0) {
			return $this->buildThumbnails($this->getFilename());
		}
	}

	private function generateMetadata($filename, $mimetype)
	{
		$this->_mime = $mimetype;
		$image = new ImageManipulator();
		$image->load($filename);
		$this->_width = $image->getWidth();
		$this->_height = $image->getHeight();
		if($mimetype == 'image/jpeg' || $mimetype == 'image/tiff') {
			$this->_exif = exif_read_data( $filename, null, true );
		}
		return true;
	}

	public function regenerateMetadata()
	{
		return $this->generateMetadata($this->getFilename(), $this->getMimeType());
	}

	public function getUserFriendlyExif($escape = false)
	{
		$exif = $this->getExif();
		$out_exif = array();

		if(isset($exif['IFD0']['Make']))			$out_exif['IFD0/Make'] = array('descr'=>__('Make'), 'val'=>$exif['IFD0']['Make']);
		if(isset($exif['IFD0']['Model']))			$out_exif['IFD0/Model'] = array('descr'=>__('Model'), 'val'=>$exif['IFD0']['Model']);
		if(isset($exif['IFD0']['DateTime']))		$out_exif['IFD0/DateTime'] = array('descr'=>__('Date and time'), 'val'=>$exif['IFD0']['DateTime']);
		if(isset($exif['IFD0']['Software']))		$out_exif['IFD0/Software'] = array('descr'=>__('Software'), 'val'=>$exif['IFD0']['Software']);
		if(isset($exif['IFD0']['Copyright']))		$out_exif['IFD0/Copyright'] = array('descr'=>__('Copyright'), 'val'=>$exif['IFD0']['Copyright']);
		if(isset($exif['IFD0']['Orientation'])) {
			$v = "";
			switch((int)$exif['IFD0']['Orientation']) {
				case 1:	$v = __("Horizontal"); break;
				case 2:	$v = __("Mirror horizontal"); break;
				case 3:	$v = __("Rotate 180"); break;
				case 4:	$v = __("Mirror vertical"); break;
				case 5:	$v = __("Mirror horizontal and rotate 270 CW"); break;
				case 6:	$v = __("Rotate 90 CW"); break;
				case 7:	$v = __("Mirror horizontal and rotate 90 CW"); break;
				case 8:	$v = __("Rotate 270 CW"); break;
			}
			$out_exif['IFD0/Orientation'] = array('descr'=>__('Orientation'), 'val'=>$v);
		}
		if(isset($exif['IFD0']['XResolution']))		$out_exif['IFD0/XResolution'] = array('descr'=>__('X resolution'), 'val'=>$exif['IFD0']['XResolution']);
		if(isset($exif['IFD0']['YResolution']))		$out_exif['IFD0/YResolution'] = array('descr'=>__('Y resolution'), 'val'=>$exif['IFD0']['YResolution']);
		if(isset($exif['IFD0']['ResolutionUnit'])) {
			$v = "";
			switch((int)$exif['IFD0']['ResolutionUnit']) {
				case 1:	$v = __("None"); break;
				case 2:	$v = __("Inches"); break;
				case 3:	$v = __("Centimeters"); break;
			}
			$out_exif['IFD0/ResolutionUnit'] = array('descr'=>__('Resolution unit'), 'val'=>$v);
		}
		if(isset($exif['COMPUTED']['ApertureFNumber']))	$out_exif['COMPUTED/ApertureFNumber'] = array('descr'=>__('Aperture'), 'val'=>$exif['COMPUTED']['ApertureFNumber']);
		if(isset($exif['EXIF']['FNumber']))		$out_exif['EXIF/FNumber'] = array('descr'=>__('F number'), 'val'=>$exif['EXIF']['FNumber']);
		if(isset($exif['EXIF']['ExposureTime']))		$out_exif['EXIF/ExposureTime'] = array('descr'=>__('Exposure time'), 'val'=>$exif['EXIF']['ExposureTime']);
		if(isset($exif['EXIF']['ISOSpeedRatings']))		$out_exif['EXIF/ISOSpeedRatings'] = array('descr'=>__('ISO'), 'val'=>$exif['EXIF']['ISOSpeedRatings']);
		if(isset($exif['EXIF']['LightSource'])) {
			$v = "";
			switch((int)$exif['EXIF']['LightSource']) {
				case 0:		$v = __("Unknown"); break;
				case 1:		$v = __("Fluorescent"); break;
				case 2:		$v = __("Fluorescent"); break;
				case 3:		$v = __("Tungsten (Incandescent)"); break;
				case 4:		$v = __("Flash"); break;
				case 9:		$v = __("Fine Weather"); break;
				case 10:	$v = __("Cloudy"); break;
				case 11:	$v = __("Shade"); break;
				case 12:	$v = __("Daylight Fluorescent"); break;
				case 13:	$v = __("Day White Fluorescent"); break;
				case 14:	$v = __("Cool White Fluorescent"); break;
				case 15:	$v = __("White Fluorescent"); break;
				case 16:	$v = __("Warm White Fluorescent"); break;
				case 17:	$v = __("Standard Light A"); break;
				case 18:	$v = __("Standard Light B"); break;
				case 19:	$v = __("Standard Light C"); break;
				case 20:	$v = __("D55"); break;
				case 21:	$v = __("D65"); break;
				case 22:	$v = __("D75"); break;
				case 23:	$v = __("D50"); break;
				case 24:	$v = __("ISO Studio Tungsten"); break;
				case 255:	$v = __("Other"); break;
			}
			$out_exif['EXIF/LightSource'] = array('descr'=>__('Light source'), 'val'=>$v);
		}
		if(isset($exif['EXIF']['Flash'])) {
			$v = "";
			switch((int)$exif['EXIF']['Flash']) {
				case 0x0:	$v = __("No Flash"); break;
				case 0x1:	$v = __("Fired"); break;
				case 0x5:	$v = __("Fired, Return not detected"); break;
				case 0x7:	$v = __("Fired, Return detected"); break;
				case 0x8:	$v = __("On, Did not fire"); break;
				case 0x9:	$v = __("On, Fired"); break;
				case 0xd:	$v = __("On, Return not detected"); break;
				case 0xf:	$v = __("On, Return detected"); break;
				case 0x10:	$v = __("Off, Did not fire"); break;
				case 0x14:	$v = __("Off, Did not fire, Return not detected"); break;
				case 0x18:	$v = __("Auto, Did not fire"); break;
				case 0x19:	$v = __("Auto, Fired"); break;
				case 0x1d:	$v = __("Auto, Fired, Return not detected"); break;
				case 0x1f:	$v = __("Auto, Fired, Return detected"); break;
				case 0x20:	$v = __("No flash function"); break;
				case 0x30:	$v = __("Off, No flash function"); break;
				case 0x41:	$v = __("Fired, Red-eye reduction"); break;
				case 0x45:	$v = __("Fired, Red-eye reduction, Return not detected"); break;
				case 0x47:	$v = __("Fired, Red-eye reduction, Return detected"); break;
				case 0x49:	$v = __("On, Red-eye reduction"); break;
				case 0x4d:	$v = __("On, Red-eye reduction, Return not detected"); break;
				case 0x4f:	$v = __("On, Red-eye reduction, Return detected"); break;
				case 0x50:	$v = __("Off, Red-eye reduction"); break;
				case 0x58:	$v = __("Auto, Did not fire, Red-eye reduction"); break;
				case 0x59:	$v = __("Auto, Fired, Red-eye reduction"); break;
				case 0x5d:	$v = __("Auto, Fired, Red-eye reduction, Return not detected"); break;
				case 0x5f:	$v = __("Auto, Fired, Red-eye reduction, Return detected"); break;
			}
			$out_exif['EXIF/Flash'] = array('descr'=>__('Flash'), 'val'=>$v);
		}
		if(isset($exif['EXIF']['ExposureMode'])) {
			$v = "";
			switch((int)$exif['EXIF']['ExposureMode']) {
				case 0:		$v = __("Auto"); break;
				case 1:		$v = __("Manual"); break;
				case 2:		$v = __("Auto bracket"); break;
			}
			$out_exif['EXIF/ExposureMode'] = array('descr'=>__('Exposure mode'), 'val'=>$v);
		}
		if(isset($exif['EXIF']['WhiteBalance'])) {
			$v = "";
			switch((int)$exif['EXIF']['WhiteBalance']) {
				case 0:		$v = __("Auto"); break;
				case 1:		$v = __("Manual"); break;
			}
			$out_exif['EXIF/WhiteBalance'] = array('descr'=>__('White balance'), 'val'=>$v);
		}

		if($escape)
			$out_exif = escape_array($out_exif);

		return $out_exif;
	}

	public static function upload_is_valid(array $file, $standard_uploader = true)
	{
		global $cfg, $GLOB;

		if($file['error'] == 0) {
			if(in_array($file['type'], $GLOB['supported_mimes'])) {
				if(!$standard_uploader || is_uploaded_file($file['tmp_name'])) {
					if($file['size'] <= $cfg['max_upload_size']) {

						// Tutti i controlli sono stati superati.
						return true;

					}
				}
			}
		}

		return false;
	}

	public function getComments()
	{

		$comments = $this->mdao->getImageCommentsFromImageId($this->_id);
		if(!empty($comments)) {
			$imgdao = new ImageCommentDao($this->mdao->getDao());
			$ret = array();
			foreach($comments as $comm) {
				$id = (int)$comm['id'];
				$ret[$id] = new ImageComment($imgdao, null, $comm);
			}
			return $ret;
		} else throw new Exception('ID does not exist.', 1000001);

	}

	// Returns an array with the id of the last $num images uploaded
	public static function getLastUploadedIds($num, $ownerid, ImageCommentDao $mdao)
	{
		$ids = $mdao->getLastUploadedIds($num, $owner_id);
		$ret = array();
		foreach($ids as $row) {
			$ret[] = (int)$row['id'];
		}
		return $ret;
	}

	// For external use
	function getAllSizes()
	{
		global $GLOB;

		$otherSizes = array();
		$origWidth = $this->getWidth();
		$origHeight = $this->getHeight();

		if($origWidth != 0 && $origHeight != 0) { // Prevent DivisionByZero on malformed DB entries

			$otherSizes_prep = array(
							'h_500' => array(
								'file'=>$this->getFilename() . '--h_500',
								'descr'=>__('Medium'),
								'link'=>$GLOB['base_url'] . '/' . $this->getSafeFilename('h_500'),
								'w'=>(int)(500 * $origWidth / $origHeight),
								'h'=>500
							),
							'h_768' => array(
								'file'=>$this->getFilename() . '--h_768',
								'descr'=>__('Medium'),
								'link'=>$GLOB['base_url'] . '/' . $this->getSafeFilename('h_768'),
								'w'=>(int)(768 * $origWidth / $origHeight),
								'h'=>768
							),
							'h_1024' => array(
								'file'=>$this->getFilename() . '--h_1024',
								'descr'=>__('Large'),
								'link'=>$GLOB['base_url'] . '/' . $this->getSafeFilename('h_1024'),
								'w'=>(int)(1024 * $origWidth / $origHeight),
								'h'=>1024
							),
							'original' => array(
								'file'=>$this->getFilename(),
								'descr'=>__('Original'),
								'link'=>$GLOB['base_url'] . '/' . $this->getSafeFilename(),
								'w'=>$origWidth,
								'h'=>$origHeight
							)
					);
			foreach($otherSizes_prep as $k => $size) {
				if(file_exists($size['file'])) {
					$otherSizes[$k] = array('descr'=>$size['descr'], 'link'=>$size['link'], 'w'=>$size['w'], 'h'=>$size['h']);
				}
			}

		}

		return $otherSizes;
	}

	function getBiggestSize($fallback_original, array $allSizes = null)
	{
		if($allSizes === null) $allSizes = $this->getAllSizes();

		$bigger = null;

		if(count($allSizes) > 0) {

			foreach($allSizes as $k => $size) {
				if(is_null($bigger) || $size['h'] > $bigger['h']) {
					if($k != 'original') {
						$bigger = $size;
					}
				}
			}

			if($bigger == null) {
				if($fallback_original)
					return $allSizes['original'];
				else
					return false;
			} else {
				return $bigger;
			}

		} else {
			return false;
		}
	}

	/*
	* Elimina una immagine esistente (dal disco e dal db).
	*
	* Restituisce "true" se l'elemento viene eliminato, oppure "false" se l'operazione fallisce.
	*/
	public static function deleteFromId($id, ImageCommentDao $mdao)
	{
		if($id > 0) {

			$img = new Image($mdao, $id);

			if(unlink($img->getFilename())) {

				// Unlink thumbnails too
				$files = glob($img->getFilename() . "--*");
				foreach($files as $file) {
					if(is_file($file)) {
						if(unlink($file))
							continue;
						else throw new Exception('Cannot delete thumbnail file.', 10100006);
					}
				}
			
				if($mdao->deleteImageFromId($id)) {
					return true;
				}

			} else throw new Exception('Cannot delete file.', 10100006);

		} else {
			throw new Exception('Cannot delete a new image.', 10100005);
		}
	}
	
}


?>

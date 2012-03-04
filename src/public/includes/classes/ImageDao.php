<?php

require_once("includes/classes/Dao.php");
require_once("includes/interfaces/DaoInterface.php");

class ImageDao implements DaoInterface {

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
	
	public function getImageFromId($id)
	{
		$id = (int)$id;

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
					hide_exif,
					description
				FROM " . $this->dao->getPrefixedTable('images') . " WHERE id = $id";

		$result = mysql_query($query);

		if ($result !== false) {
			if(mysql_num_rows($result) > 0) {
				return mysql_fetch_assoc($result);
			} else return false;
		} else throw new Exception('Query error.', 10000002);
	}

	public function updateImageFromId($id, $display_name, $owner_id, $exif, DateTime $upload_time, $tags, $width, $height, $mime, $hide_exif, $description)
	{
		$upload_time->setTimezone(new DateTimeZone('UTC'));

		$sql = "UPDATE " . $this->dao->getPrefixedTable('images') . " SET 
					display_name = '" 		. mysql_real_escape_string($display_name) . "',
					owner_id = " 			. (int)$owner_id . ",
					exif = '"				. mysql_real_escape_string($exif) . "',
					upload_time = '"		. $upload_time->format('Y-m-d H:i:s') . "',
					tags = '"				. mysql_real_escape_string($tags) . "',
					width = " 				. (int)$width . ",
					height = " 				. (int)$height . ",
					mime = '"				. mysql_real_escape_string($mime) . "',
					hide_exif = "			. (int)$hide_exif . ",
					description = '"		. mysql_real_escape_string($description) . "'
				WHERE (id = " . (int)$id . ")";

		if(mysql_query($sql)) {
			return true;
		} else throw new Exception('Query error.', 10000002); 
	}

	public function insertImage($display_name, $file_name, $owner_id, $exif, DateTime $upload_time, $tags, $width, $height, $mime, $hide_exif, $description)
	{
		$datetime->setTimezone(new DateTimeZone('UTC'));

		$sql = "INSERT INTO " . $this->dao->getPrefixedTable('images') . "
				(display_name, file_name, owner_id, exif, upload_time, tags, width, height, mime, hide_exif, description)
				VALUES (
					'" . mysql_real_escape_string($display_name) . "',
					'" . mysql_real_escape_string($file_name) . "',
					" . (int)$owner_id . ",
					'" . mysql_real_escape_string($exif) . "',
					'" . $upload_time->format('Y-m-d H:i:s') . "',
					'" . mysql_real_escape_string($tags) . "',
					" . (int)$width . ",
					" . (int)$height . ",
					'" . mysql_real_escape_string($mime) . "',
					" . (int)$hide_exif . ",
					'" . mysql_real_escape_string($description) . "'
				)";

		if(mysql_query($sql)) {

			// Get last inserted id
			return $this->getImageIdFromFilename($file_name);

		} else throw new Exception('Query error.', 10000002); 
	}

	private function getImageIdFromFilename($filename)
	{
		$query = "SELECT 
					id
				FROM " . $this->dao->getPrefixedTable('images') . " WHERE file_name = '" . mysql_real_escape_string($filename) . "'";

		$result = mysql_query($query);

		if ($result !== false) {
			if(mysql_num_rows($result) > 0) {
				$row = mysql_fetch_assoc($result);
				return (int)$row['id'];
			} else return -1;
		} else throw new Exception('Query error.', 10000002);
	}

	public function getImageCommentsFromImageId($image_id)
	{
		$mdao = new ImageCommentDao($this->getDao());
		return $mdao->getImageCommentsFromImageId($image_id);
	}

	public function getLastUploadedIds($num, $ownerid)
	{
		$num = (int)$num;

		$limit = '';
		if($num > -1) $limit = "LIMIT $num";

		$where = '';
		if($ownerid > -1) $where = "WHERE owner_id = $ownerid";

		$query = "SELECT 
					id
				FROM " . $this->dao->getPrefixedTable('images') . "
				$where
				ORDER BY upload_time DESC
				$limit";

		$result = mysql_query($query);

		if ($result !== false) {
			$ret = array();
			while($row = mysql_fetch_assoc($result)) {
				$ret[] = $row;
			}
			return $ret;
		} else throw new Exception('Query error.', 10000002);
	}

	public function deleteImageFromId($id)
	{
		$id = (int)$id;

		$query = "DELETE FROM " . $this->dao->getPrefixedTable('images') . "
				WHERE (id = $id)";
		if(mysql_query($query)) {
			return true;
		} else throw new Exception('Query error.', 10000002);
	}

}

?>

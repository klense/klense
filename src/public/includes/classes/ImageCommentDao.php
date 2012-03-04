<?php

require_once("includes/classes/Dao.php");
require_once("includes/interfaces/DaoInterface.php");

class ImageCommentDao implements DaoInterface {

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
	
	public function getImageCommentFromId($id)
	{
		$id = (int)$id;

		$query = "SELECT 
					id,
					user_id,
					image_id,
					datetime,
					content
				FROM " . $this->dao->getPrefixedTable('images_comments') . " WHERE id = $id";

		$result = mysql_query($query);

		if ($result !== false) {
			if(mysql_num_rows($result) > 0) {
				return mysql_fetch_assoc($result);
			} else return false;
		} else throw new Exception('Query error.', 10000002);
	}

	public function getImageCommentsFromImageId($image_id)
	{
		$query = "SELECT " . $this->dao->getPrefixedTable('images_comments') . ".*
				FROM " . $this->dao->getPrefixedTable('images_comments') . " WHERE
					image_id = " . (int)$image_id . "
				ORDER BY datetime desc";

		$result = mysql_query($query);

		if ($result !== false) {
			$ret = array();
			while($row = mysql_fetch_assoc($result)) {
				$ret[] = $row;
			}
			return $ret;
		} else throw new Exception('Query error.', 10000002);
	}

	public function updateImageCommentFromId($id, $user_id, $image_id, DateTime $datetime, $content)
	{
		$datetime->setTimezone(new DateTimeZone('UTC'));

		$sql = "UPDATE " . $this->dao->getPrefixedTable('images_comments') . " SET 
					user_id = " 	. (int)$user_id . ",
					image_id = "	. (int)$image_id . ",
					datetime = '" 	. $datetime->format('Y-m-d H:i:s') . "',
					content = '" 	. mysql_real_escape_string($content) . "'
				WHERE (id = " . (int)$id . ")";

		if(mysql_query($sql)) {
			return true;
		} else throw new Exception('Query error.', 10000002); 
	}

	public function insertImageComment($user_id, $image_id, DateTime $datetime, $content)
	{
		$datetime->setTimezone(new DateTimeZone('UTC'));

		$sql = "INSERT INTO " . $this->dao->getPrefixedTable('images_comments') . "
				(user_id, image_id, datetime, content)
				VALUES (
						" . (int)$user_id . ",
						" . (int)$image_id . ",
					'" . $datetime->format('Y-m-d H:i:s') . "',
					'" . mysql_real_escape_string($content) . "'
				)";

		if(mysql_query($sql)) {

			// Get last inserted id
			$query = "SELECT id FROM " . $this->dao->getPrefixedTable('images_comments') . " WHERE 
						user_id = " 		. (int)$user_id . "
						AND image_id = " 	. (int)$image_id . "
						AND datetime = '" 	. $datetime->format('Y-m-d H:i:s') . "'
					";

			$result = mysql_query($query);

			if ($result !== false) {
				if(mysql_num_rows($result) > 0) {
					$row = mysql_fetch_assoc($result);
					return (int)$row['id'];
				} else throw new Exception('Inserted id not found.', 10000002);;
			} else throw new Exception('Query error.', 10000002);

		} else throw new Exception('Query error.', 10000002); 
	}

	public function deleteImageCommentFromId($id)
	{
		$id = (int)$id;

		$query = "DELETE FROM " . $this->dao->getPrefixedTable('images_comments') . "
				WHERE (id = $id)";
		if(mysql_query($query)) {
			return true;
		} else throw new Exception('Query error.', 10000002);
	}

}

?>

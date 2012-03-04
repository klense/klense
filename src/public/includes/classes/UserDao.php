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
	
	public function getImageViews($from_date, $to_date, $image_id, $output_mode)
	{
		$image_id = (int)$image_id;
		$from_date->setTimezone(new DateTimeZone('UTC'));
		$to_date->setTimezone(new DateTimeZone('UTC'));

		$query = "SELECT image_id, date, views
					FROM " . $this->dao->getPrefixedTable('images_views') . " WHERE
					image_id = $image_id
					AND date BETWEEN '" . $from_date->format('Y-m-d') . "' AND '" . $to_date->format('Y-m-d') . "'
					ORDER BY date ASC";

		$result = mysql_query($query);

		if ($result !== false) {
			$ret = array();
			while($row = mysql_fetch_assoc($result)) {
				$ret[] = $row;
			}
			return $ret;
		} else throw new Exception('Query error.', 10000002);
	}

	public function addImageView($image_id, DateTime $datetime)
	{
		$datetime->setTimezone(new DateTimeZone('UTC'));

		$sql = "INSERT INTO " . $this->dao->getPrefixedTable('images_views') . "
				(image_id, date, views)
				VALUES (
					 " . (int)$image_id . ",
					'" . $datetime->format('Y-m-d') . "',
					 " . "1" . "
				) ON DUPLICATE KEY UPDATE views=views+1";

		if(mysql_query($sql)) {
			return true;
		} else throw new Exception('Query error.'.$sql, 10100002);
	}

}

?>

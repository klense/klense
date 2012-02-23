<?php

class PageView {

	const OutputMode_SimpleYXComma = 1;

	public static function addImageView($image_id)
	{
		global $cfg;

		$image_id = (int)$image_id;
		$now = new DateTime('now', new DateTimeZone('UTC'));

		$sql = "INSERT INTO {$cfg['table_prefix']}_images_views
				(image_id, date, views)
				VALUES (
					 " . $image_id . ",
					'" . $now->format('Y-m-d') . "',
					 " . "1" . "
				) ON DUPLICATE KEY UPDATE views=views+1";

		if(mysql_query($sql)) {
			return true;
		} else throw new Exception('Query error.'.$sql, 10100002);
	}

	public static function getImageViews($from_date, $to_date, $image_id, $output_mode)
	{
		global $cfg;

		$image_id = (int)$image_id;

		$query = "SELECT image_id, date, views
					FROM {$cfg['table_prefix']}_images_views WHERE
					image_id = $image_id
					AND date BETWEEN '" . $from_date->format('Y-m-d') . "' AND '" . $to_date->format('Y-m-d') . "'
					ORDER BY date ASC";

		$result = mysql_query($query);

		if ($result !== false) {

			if($output_mode == self::OutputMode_SimpleYXComma) {
				$ret = '';

				while($row = mysql_fetch_assoc($result)) {
					$ret .= strtotime($row['date']) . ':' . $row['views'] . ',';
				}
	
				$ret = rtrim($ret, ',');
				return $ret;
			}
		} else throw new Exception('Query error.', 10000002);
	}
	
}


?>

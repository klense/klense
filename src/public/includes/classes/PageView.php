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
				$data = array();

				while($row = mysql_fetch_assoc($result)) {
					$db_timestamp = strtotime($row['date']);
					$data[$db_timestamp] = $row['views'];
				}

				// Fill gaps
				$ret = '';
				$start_timestamp = strtotime($from_date->format('Y-m-d'));
				$end_timestamp = strtotime($to_date->format('Y-m-d'));
				for($i = $start_timestamp; $i <= $end_timestamp; $i += 86400) {
					if(isset($data[$i])) {
						$ret .= $i . ':' . $data[$i] . ',';
					} else {
						$ret .= $i . ':0,';
					}
				}
	
				$ret = rtrim($ret, ',');
				return $ret;
			}
		} else throw new Exception('Query error.', 10000002);
	}
	
}


?>
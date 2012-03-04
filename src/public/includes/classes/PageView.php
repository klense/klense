<?php

require_once("includes/interfaces/DatabaseInterface.php");

class PageView {

	const OutputMode_SimpleYXComma = 1;

	public static function addImageView($image_id, PageViewDao $mdao)
	{
		$now = new DateTime('now', new DateTimeZone('UTC'));

		return $mdao->addImageView($image_id, $now);
	}

	public static function getImageViews($from_date, $to_date, $image_id, $output_mode, PageViewDao $mdao)
	{
		$result = $mdao->getImageViews($from_date, $to_date, $image_id);

		if($output_mode == self::OutputMode_SimpleYXComma) {
			$data = array();

			foreach($result as $row) {
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
	}
	
}


?>

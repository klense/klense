<?php

function to_utf8($str)
{
	if(mb_check_encoding($str, 'UTF-8')) {
		return $str;
	} else {
		return utf8_encode($str);
	}
}

function htmles($string)
{
	$flags = ENT_COMPAT;
	if(defined("ENT_HTML5")) $flags |= ENT_HTML5;
	return htmlspecialchars($string, $flags, 'UTF-8', true);
}

// Genera testo HTML da inserire dentro agli elementi INPUT o TEXTAREA (dato che i ritorni a capo e gli spazi non vengono convertiti in entita' html)
function show_input_html($string)
{
	$string = to_utf8($string);
	$str = htmlentities($string, ENT_QUOTES, 'UTF-8');
	return utf8_encode($str);

	////$str = str_replace("  ", "&nbsp; ", htmlspecialchars($string));
	////$enc = mb_detect_encoding($string, "ASCII,UTF-8");
	//$str = htmlentities($string, ENT_QUOTES, 'UTF-8');
	//if(strlen($str) == 0 && strlen($string) > 0) {
		//// Durante la conversione c'e' stato un problema relativo alla codifica.
		//// Provo con un'altra codifica
		//$str = htmlentities($string, ENT_QUOTES, 'ISO8859-15');
	//}

	//return utf8_encode($str);
}

function show_html($string)
{
	$str = show_input_html($string);
	$str = nl2br_x($str);
	$str = str_replace('  ','&nbsp;&nbsp;',$str);
	return $str;
}

function nl2br_x($string) {
	// La funzione originale nl2br($str, true); creava problemi: su alcune macchine restituiva una stringa completamente vuota.
	$string = str_replace("\r\n",'<br />',$string);
	$string = str_replace("\n",'<br />',$string);
	$string = str_replace("\r",'<br />',$string);
	return $string;
}

function jsescape1($str) {
	return str_replace("'", '\\\'', $str);  // sostituisce ' con \'
}

function jsescape2($str) {
	return str_replace('"', '\"', $str);    // sostituisce " con \"
}

function getRealIpAddress()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function validFileName($item) {
	if (!preg_match('%^[a-zA-Z0-9\' _-](?:[a-zA-Z0-9\' _-]|\.(?!\.\\\\|\./|$))+$%iD', $item)) {
	   return false;
	}
	return true;
	/* if((strpos($item, "..\\") === 0) || (strpos($item, '../') === 0)
	  || (strpos($item, "\n") !== false) || (strpos($item, '"') !== false)
	  || (strpos($item, ":") !== false) || (strpos($item, '\\') !== false)
	  || (strpos($item, "/") !== false) || (strpos($item, '"') !== false)) {
			return false;
		} else {
			return true;
		} */
}

function strptotime($time) {
	$datetime = '%^(?P<giorno>[1-9]|0[1-9]|[12][0-9]|3[01])[-\\\\ /.](?P<mese>[1-9]|0[1-9]|1[012])[-\\\\ /.](?P<anno>(19|20)\d\d)( (?P<ore>[0-9]|0[0-9]|1[0-9]|2[0-3])[:.](?P<minuti>[0-9]|[0-5][0-9])([:.](?P<secondi>[0-9]|[0-5][0-9]))?)?$%';
	$groups = array();
	if(preg_match($datetime, $time, $groups)) {
		//echo $groups[0]. '---' . $groups['giorno'] .' '. $groups['mese'] .' '. $groups['anno'] .' '. $groups['ore'] .' '. $groups['minuti'] .' '. $groups['secondi'];
		$engstring = $groups['anno'].'-'.$groups['mese'].'-'.$groups['giorno'];
		if(isset($groups['ore'])) $engstring .= ' '.$groups['ore'];
		if(isset($groups['minuti'])) $engstring .= ':'.$groups['minuti'];
		if(isset($groups['secondi'])) $engstring .= ':'.$groups['secondi'];
		$tt = strtotime($engstring);
		if($tt !== false && $tt !== -1) {
			return $tt;
		} else {
			return false;
		}
	} else if(strtotime($time) !== false && strtotime($time) !== -1) {
		return strtotime($time);
	} else {
		return false;
	}
}

function validtime($time) {
	$datetime = '%^(?P<ore>[0-9]|0[0-9]|1[0-9]|2[0-3])[:.](?P<minuti>[0-9]|[0-5][0-9])([:.](?P<secondi>[0-9]|[0-5][0-9]))?$%';
	$groups = array();
	if(preg_match($datetime, $time, $groups)) {
		return true;
	} else {
		return false;
	}
}

function validEmailAddress($email)
{
	if(mb_strpos($email, '@') !== false && mb_strpos($email, '.') !== false) {
		return true;
	} else {
		return false;
	}
}

function startsWith($haystack,$needle,$case=true) {
    if($case){return (strcmp(mb_substr($haystack, 0, mb_strlen($needle)),$needle)===0);}
    return (strcasecmp(mb_substr($haystack, 0, mb_strlen($needle)),$needle)===0);
}

function endsWith($haystack,$needle,$case=true) {
    if($case){return (strcmp(mb_substr($haystack, mb_strlen($haystack) - mb_strlen($needle)),$needle)===0);}
    return (strcasecmp(mb_substr($haystack, mb_strlen($haystack) - mb_strlen($needle)),$needle)===0);
}

// Conta gli elementi in un array escludendo quelli vuoti
function ecount($array)
{
	$n = 0;
	foreach($array as $itm) {
		if($itm != '') {
			$n++;
		}
	}
	return $n;
}

function return_bytes($val)
{
    $val = trim($val);
    $last = mb_strtolower(mb_substr($val, mb_strlen($val)-2, 1));
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}

function mailquote($text, $marker, $length){
  $text = $marker . str_replace("\n", "\n$marker", wordwrap($text, $length));
  return $text;
}

function timeBetweenDates($from_time, $to_time, $showSeconds)
{
	$from_time = (int)$from_time;
	$to_time = (int)$to_time;
	$diff = $to_time - $from_time;
	
	$ore = (int)( $diff / 60 / 60 );
	$minuti = (int)( ($diff - 3600*$ore) / 60 );
	$secondi = (int)( $diff - 3600*$ore - 60*$minuti );

	$ret_str = $ore . '.' . str_pad($minuti, 2, "0", STR_PAD_LEFT);
	if($showSeconds) $ret_str .= '.' . str_pad($secondi, 2, "0", STR_PAD_LEFT);;

	return $ret_str;
}

function niceTimeInterval_hours($strTimeInterval)
{
	$vals = explode('.', $strTimeInterval, 3);
	for($i=0; $i < count($vals); $i++) {
		$vals[$i] = (int)$vals[$i];
	}
	
	$ret_str = '';
	if($vals[0] > 0) {
		if($vals[0] == 1)
			$ret_str .= $vals[0] . " ora";
		else
			$ret_str .= $vals[0] . " ore";
	}
	if(isset($vals[1]) && $vals[1] > 0) {
		if($ret_str != '') $ret_str .= ', ';

		if($vals[1] == 1)
			$ret_str .= $vals[1] . " minuto";
		else
			$ret_str .= $vals[1] . " minuti";
	}
	if(isset($vals[2]) && $vals[2] > 0) {
		if($ret_str != '') $ret_str .= ', ';

		if($vals[2] == 1)
			$ret_str .= $vals[2] . " secondo";
		else
			$ret_str .= $vals[2] . " secondi";
	}

	if($ret_str == '') $ret_str = '0';
	
	return $ret_str;
}

function dateTimeAgo($data_ref)
{

	// Get the current date
	$current_date = date('Y-m-d H:i:s');

	// Extract from $current_date
	$current_year = mb_substr($current_date,0,4);
	$current_month = mb_substr($current_date,5,2);
	$current_day = mb_substr($current_date,8,2);

	// Extract from $data_ref
	$ref_year = mb_substr($data_ref,0,4);
	$ref_month = mb_substr($data_ref,5,2);
	$ref_day = mb_substr($data_ref,8,2);

	// create a string yyyymmdd 20071021
	$tempMaxDate = $current_year . $current_month . $current_day;
	$tempDataRef = $ref_year . $ref_month . $ref_day;

	$tempDifference = $tempMaxDate-$tempDataRef;

	// If the difference is GT 10 days show the date
	if($tempDifference >= 10){
		return false;
	} else {

		// Extract $current_date H:m:ss
		$current_hour = mb_substr($current_date,11,2);
		$current_min = mb_substr($current_date,14,2);
		$current_seconds = mb_substr($current_date,17,2);

		// Extract $data_ref Date H:m:ss
		$ref_hour = mb_substr($data_ref,11,2);
		$ref_min = mb_substr($data_ref,14,2);
		$ref_seconds = mb_substr($data_ref,17,2);

		$hDf = $current_hour-$ref_hour;
		$mDf = $current_min-$ref_min;
		$sDf = $current_seconds-$ref_seconds;

		// Show time difference ex: 2 min 54 sec ago.
		if($dDf<1){
			if($hDf>0){
				if($mDf<0){
					$mDf = 60 + $mDf;
					$hDf = $hDf - 1;
					return $mDf . ' min ago';
				} else {
					return $hDf. ' hr ' . $mDf . ' min ago';
				}
			} else {
				if($mDf>0){
					return $mDf . ' min ' . $sDf . ' sec ago';
				} else {
					return $sDf . ' sec ago';
				}
			}
		} else {
			return $dDf . ' days ago';
		}
	}
}

function currentFullBaseUrl() { 
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$tm = mb_strtolower($_SERVER["SERVER_PROTOCOL"]);
	$protocol = mb_substr($tm, 0, mb_strpos($tm, '/')) . $s; 
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]); 

	$ret = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port; 
	
	$self_dir = dirname($_SERVER['PHP_SELF']);
	if($self_dir[0] != '/' && endsWith($ret, '/') == false) $ret .= '/'; // Aggiunge slash finale se serve
	$ret .= $self_dir;

	if(!endsWith($ret, '/')) $ret .= '/'; // Aggiunge eventuale slash finale

	return $ret;
} 

function strleft($s1, $s2) { return mb_substr($s1, 0, mb_strpos($s1, $s2)); }

function selfURL($requestUri) { 
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = strleft(mb_strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; 
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
	$ret = $protocol."://".$_SERVER['SERVER_NAME'].$port; 
	if($requestUri) $ret .= $_SERVER['REQUEST_URI'];
	return $ret;
} 

/* Esegue il redirect a una pagina
$dest indica l'indirizzo di destinazione relativo (es. "index.php")
*/
function internal_redirect($dest)
{

	header("location: " . currentFullBaseUrl() . $dest);
/*
	if(isset($param)) {
		$url = parse_url($param);
		$redir = selfURL(false);
		if(isset($url['path'])) {
	 		if($url['path'][0] != '/') $redir = '/' . $redir;
			$redir .= $url['path'];
			if(isset($url['query'])) $redir .= '?' . $url['query'];
			if(isset($url['fragment'])) $redir .= '#' . $url['fragment'];
			header("location: " . $redir);
		} else {
			header("location: " . currentFullBaseUrl() . $dest);
		}
	} else {
		header("location: " . currentFullBaseUrl() . $dest);
	}
*/
}

function getErrorString($e)
{
	return 'Errore ' . mb_strtoupper(dechex($e->getCode())) . ': ' . $e->getMessage();
}


function rearrange_files_array( $arr ){
	$new = array();
	foreach( $arr as $key => $all ){
		foreach( $all as $i => $val ){
			if($arr['name'][$i] != '') {
				$new[$i][$key] = $val;   
			}
		}   
	}
	return $new;
}

function pageNotFound()
{
	header("HTTP/1.0 404 Not Found");
	echo "404. Your page is not here. Really. Sorry.";
	exit();
}

?>

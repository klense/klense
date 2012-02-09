<?php
// Operazioni di inizializzazione globali


// Load configuration
require_once("config.php");

// Load internationalization file
require_once("includes/i18n.php");

// Load utilities file
require_once("includes/utils.php");


// Inizializza Smarty
require_once('includes/libs/smarty/libs/Smarty.class.php');
$smarty = new Smarty;
$smarty->template_dir = 'content/style/templates';
$smarty->compile_dir = 'includes/libs/smarty/templates_c';
$smarty->config_dir = 'includes/libs/smarty/configs';
$smarty->cache_dir = 'includes/libs/smarty/cache';


// Inizializza $GLOB
$GLOB = array();
$GLOB['supported_mimes'] = array('image/jpeg', 'image/png');

// Imposta i valori per l'upload dei file
ini_set('upload_max_filesize', $cfg['max_upload_size']);

// Inizializza la connessione al DB
mysql_connect($cfg['dbhost'], $cfg['dbuser'], $cfg['dbpass']);
mysql_select_db($cfg['dbname']);

// Autoload classes
function klense_autoload($class_name)
{
	// $a = My_New_Class ==> includes/classes/My/New/Class.php
	$class_name_p = str_replace("_", "/", $class_name);
	$path = 'includes/classes/' . $class_name_p . '.php';
	if(file_exists($path)) {
		require_once($path);
	} else throw new Exception("Unable to load $class_name.");
}
spl_autoload_register('klense_autoload');

?>

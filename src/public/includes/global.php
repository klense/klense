<?php
// Operazioni di inizializzazione globali


// Carica la configurazione
require_once("config.php");


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

?>

<?php

	if(!isset($GLOB)) die();

	$smarty->assign('pageTitle', "Backmon");
	$smarty->assign('back_link', '?m=overview');
	$smarty->assign('back_text', '« Lista profili');
	$smarty->assign('greenMessage', '');

	if(isset($_GET['id'])) {

		if($_GET['id'] == 'add') {
			$new_name = tempnam($cfg['PATH'] . "customers/", 'auto-');
			rename($new_name, $new_name . '.php-off');
			$new_name = $new_name . '.php-off';
			$safe_name = basename($new_name);

			// Crea un template vuoto per il nuovo file
			$output = <<<OUTPUT
<?php 
      \$custm_mailid = '';
      \$cfg['customers'][\$custm_mailid]['NAME'] = 'Nuovo';
      \$cfg['customers'][\$custm_mailid]['SUCCESS_STRING'] = '';
      \$cfg['customers'][\$custm_mailid]['FAIL_STRING'] = '';
      \$cfg['customers'][\$custm_mailid]['BACKUP_PERIOD'] = array();
?>
OUTPUT;

			$fh = fopen($new_name, 'w') or die("Errore durante l'apertura in scrittura del file. Controllare i permessi.");
			fwrite($fh, $output);
			fclose($fh);
			chmod($new_name, 0666);

		} else {
			$safe_name = str_replace(array('/', '\\'), '_', base64_decode($_GET['id']));
		}
		$filename = $cfg['PATH'] . "customers/" . $safe_name;

		if(file_exists($filename)) {

			if(endsWith($filename, '.php-off'))
				$enabled = false;
			else if(endsWith($filename, '.php'))
				$enabled = true;
			else
				$not_valid = true;

			if(!isset($not_valid)) {

				// Aggiorna il file di configurazione, se richiesto
				if($_SERVER['REQUEST_METHOD'] == 'POST') {

					// Click sul pulsante di eliminazione
					if(isset($_POST['btnElimina'])) {
						unlink($filename);
						header("location: " . currentFullBaseUrl() . '?m=overview');
						exit();

					} else if(isset($_POST['btnModifica'])) {

						$custm_mailid = addcslashes($_POST['email'], '\'\\'); // Esegue l'escape per essere inserita in modo sicuro tra apici singoli nel codice php
						$customer_name = addcslashes($_POST['name'], '\'\\');
						$customer_success_string = addcslashes($_POST['success_string'], '\'\\');
						$customer_fail_string = addcslashes($_POST['fail_string'], '\'\\');

						$b_periods = "";
						// Iniziamo da 1 perché escludiamo la prima riga che è il template
						for($i=1; $i<count($_POST['period']); $i++) {
							$b_period = addcslashes($_POST['period'][$i], '\'\\');

							if($_POST['period'][$i] == 'everyday')
								$b_day = 0;
							else if($_POST['period'][$i] == 'day of week')
								$b_day = (int)$_POST['days_dayofweek'][$i];
							else if($_POST['period'][$i] == 'day of month')
								$b_day = (int)$_POST['days_dayofmonth'][$i];
							else die('Error');

							$b_hour = (int)$_POST['hour'][$i];
							$b_minutes = (int)$_POST['minutes'][$i];
							$b_delay = (int)$_POST['delay'][$i];

							$b_periods .= "      \$cfg['customers'][\$custm_mailid]['BACKUP_PERIOD'][] = array('$b_period', $b_day, $b_hour, $b_minutes, $b_delay);\n";

						}

						$output = <<<OUTPUT
<?php 

      // Indirizzo che identifica le mail di questo cliente
      \$custm_mailid = '$custm_mailid';

      // Nome cliente
      \$cfg['customers'][\$custm_mailid]['NAME'] = '$customer_name';
      // Testo nell'oggetto della mail che identifica un backup completato con successo
      \$cfg['customers'][\$custm_mailid]['SUCCESS_STRING'] = '$customer_success_string';
      // Testo nell'oggetto della mail che identifica un backup fallito
      \$cfg['customers'][\$custm_mailid]['FAIL_STRING'] = '$customer_fail_string';

      // Periodicità del backup
      \$cfg['customers'][\$custm_mailid]['BACKUP_PERIOD'] = array();
$b_periods

/*
Giorno
	- everyday: tutti i giorni
	- day of week: giorni della settimana: [lun, mar, mer, gio, ven, sab, dom]
	- day of month: [1°, 2°, 3°, 4°, ..., 28°, 29°, 30°, 31°] giorni del mese

Ora mail:
	- 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23

Ritardo ammesso:
	- 1h, 2h, 3h, 4h, ...


array {
	[i] => [tipo_periodo, info_periodo, ore, minuti, ritardo]

	[0] => [everyday, , 23, 0, 1]
	[1] => [day of week, 7, 23, 0, 1]
	[2] => [day of month, 30, 23, 0, 1] // 31 = ultimo giorno
}

*/
?>
OUTPUT;

						$fh = fopen($filename, 'w') or die("Errore durante l'apertura in scrittura del file. Controllare i permessi.");
						fwrite($fh, $output);
						fclose($fh);

						// Rinomina il file per attivarlo / disattivarlo, e aggiorna $enabled
						// Inoltre, rinomina il file in modo da usare il nome scelto ($_POST[name])
						$filename_noext = preg_replace("/[^a-zA-Z0-9]/", "_", $_POST['name']);
						if(isset($_POST['enabled'])) {
							$enabled = true;
							$filename_new = dirname($filename) . '/' . $filename_noext . '.php';
						} else {
							$enabled = false;
							$filename_new = dirname($filename) . '/' . $filename_noext . '.php-off';
						}

						rename($filename, $filename_new);
						$filename = $filename_new;

						$smarty->assign('greenMessage', htmles("Il profilo è stato aggiornato"));
					}
				}

				// Carica il file di configurazione
				{
					require_once "$filename";

					$smarty->assign('profile__email', htmles($custm_mailid));
					$smarty->assign('profile__name', htmles($cfg['customers'][$custm_mailid]['NAME']));
					$smarty->assign('profile__success_string', htmles($cfg['customers'][$custm_mailid]['SUCCESS_STRING']));
					$smarty->assign('profile__fail_string', htmles($cfg['customers'][$custm_mailid]['FAIL_STRING']));
					$smarty->assign('profile__enabled', $enabled);

					$backup_entries = array();
					// Creiamo il template invisibile con id=0, che servirà per aggiungere righe dinamicamente
					$backup_entries[] = array(	'period' => 'everyday',
												'day' => 1,
												'hour' => 0,
												'minutes' => 0,
												'delay' => 5
											);

					// Aggiungiamo all'array i dati veri
					foreach($cfg['customers'][$custm_mailid]['BACKUP_PERIOD'] as $entry) {
						$backup_entries[] = array(	'period' => $entry[0],
													'day' => (int)$entry[1],
													'hour' => (int)$entry[2],
													'minutes' => (int)(floor((int)$entry[3] / 5) * 5),
													'delay' => ((int)$entry[4] > 10) ? 10 : (int)$entry[4]
												);
					}

					$smarty->assign('profile__periods', $backup_entries);
					
				}
	
				$smarty->assign('profile_id', base64_encode(basename($filename)));

			} else {
				die("Errore");
			}
		} else {
			die("Errore");
		}
	} else {
		die("Errore");
	}


	$smarty->display('profile.tpl');
?>
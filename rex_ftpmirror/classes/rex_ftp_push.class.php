<?php

/**
* helper-class and functions
*
* @author hirbod mirjavadi
*/

class rex_ftp_push {

	private static $initialized					= false;
	private static $debug						= true;
	private static $myREX						= false;

	function __construct() {
		self::init();
	}

	private function init() {
		if (self::$initialized){
			return;
		}

		global $REX;
		self::$initialized = true;
		self::$debug = $debug;
	}

	public static function checkConfig() {
		global $REX;
		$myREX = $REX['ADDON']['rex_ftpmirror'];

		if(stripslashes($myREX['settings']['TEXTINPUT'][1]) && stripslashes($myREX['settings']['TEXTINPUT'][2] && stripslashes($myREX['settings']['TEXTINPUT'][3]))) {
			return true;
		} else {
			return false;
		}
	}

	public static function checkConnection() {
		global $REX;
		$myREX = $REX['ADDON']['rex_ftpmirror'];

		$return = array();

		// Verbindung aufbauen
		$conn_id = @ftp_connect($myREX['settings']['TEXTINPUT'][1]);

		if($conn_id) {

			// Anmeldung versuchen
			if (@ftp_login($conn_id, $myREX['settings']['TEXTINPUT'][2], $myREX['settings']['TEXTINPUT'][3])) {

				$return['status'] = true;
				$return['message'] = 'Verbindung hergestellt. Zugangsdaten wurden akzeptiert';

			} else {
				
				$return['status'] = false;
				$return['message'] = 'Die Verbindung zum Server konnte hergestellt werden, die Zugangsdaten wurden jedoch abgelehnt. Bitte prüfe in den Einstellungen deine Zugangsdaten!';

			}

			@ftp_close($conn_id);

		} else {
			$return['status'] = false;
			$return['message'] = 'Keine Verbindung zum Server möglich. Bitte Einstellung für FTP-Server prüfen!';
		}



		return json_encode($return);
	}

	public static function push() {

		set_time_limit(0);

		global $REX;
		$myREX = $REX['ADDON']['rex_ftpmirror'];
		$return = array();
		$config = array();

		$config['log_path'] = str_replace('/classes', '', dirname(__FILE__)).'/logs/';

		$ftp = new ftp($config);
		$ftp->conn($myREX['settings']['TEXTINPUT'][1], $myREX['settings']['TEXTINPUT'][2], $myREX['settings']['TEXTINPUT'][3]);
		$ftp->put( (($out = $myREX['settings']['TEXTINPUT'][4]) ? $out : ''), (($out = $myREX['settings']['TEXTINPUT'][5]) ? $out : $_SERVER['DOCUMENT_ROOT']));

		$return['status'] = true;
		
		return json_encode($return);
	}

}
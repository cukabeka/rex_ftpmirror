<?php

/**
* helper-class and functions
*
* @author hirbod mirjavadi
*/

class rex_ftp_push_console extends rex_ftp_push {

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

	public static function getConnection() {
		return self::checkConnection();
	}

	public static function checkConnection() {
		global $REX;
		$myREX = $REX['ADDON']['rex_ftpmirror'];

		$error = true;
		$method = false;
		$lftp = false;
		$scp = false;
		$return = array();


		if(function_exists('exec')) {
			$error = false; 
			$method = "exec";
		}


		if(function_exists('shell_exec')) {
			$error = false;
			$method = "shell_exec"; 
		}


		switch($method) {

			case "exec":
				$lftp = exec('which lftp');
				//$scp = exec('which scp');
			break;

			case "shell_exec":
				$lftp = shell_exec('which lftp');
				//$scp = shell_exec('which scp');
			break;


		}

		$lftp = trim($lftp);
		$scp = trim($scp);


		if($error && $method) {
			$return['status'] = false;
			$return['message'] = 'Weder Zugriff auf exec noch shell_exec. Mindestens eine der Funktionen muss in der php.ini aktiviert sein!';
		} elseif (!$error && !$lftp && !$scp) {
			$return['status'] = false;
			$return['message'] = 'Zugriff auf die Konsole war erfolgreich, jedoch konnte LFTP nicht gefunden werden. Leider kann die console Version nicht genutzt werden! Unterstützung für SCP und RSYNC ist bereits in der Entwicklung';
		} else {

			$return['execWith'] = $method;
			$return['server'] = $myREX['settings']['TEXTINPUT'][1];
			$return['user'] = $myREX['settings']['TEXTINPUT'][2];
			$return['pass'] = $myREX['settings']['TEXTINPUT'][3];
			$return['protocol'] = $myREX['settings']['SELECT'][1];
			$return['port'] = (($out = $myREX['settings']['TEXTINPUT'][6]) ? $out : false);
			$return['lftp'] = ($lftp ? true : false);
			$return['scp'] = ($scp ? true : false);
			$return['status'] = true;
			$return['message'] = "System kann verwendet werden, Console erreichbar und Software gefunden: $lftp $scp";
			$return['systemmethod'] = $method;
			$return['remotepath'] = (($out = stripslashes($myREX['settings']['TEXTINPUT'][7])) ? $out : '/');
			$return['localpath'] = (($out = stripslashes($myREX['settings']['TEXTINPUT'][8])) ? $out : $_SERVER['DOCUMENT_ROOT']);
			$return['transfermethod'] = $myREX['settings']['SELECT'][3];
			$return['onlynew'] = $myREX['settings']['SELECT'][4];

			if($lftp && stripslashes($myREX['settings']['SELECT'][2]) == "lftp") {
				$return['method_text'] = "Software LFTP wird genutzt (bevorzugt und vorhanden)";
				$return['final_method'] = $lftp;
				$return['software'] = 'lftp';
			}

			if($scp && stripslashes($myREX['settings']['SELECT'][2]) == "scp") {
				$return['method_text'] = "Software SCP wird genutzt (bevorzugt und vorhanden)";
				$return['final_method'] = $scp;
				$return['software'] = 'scp';
			}

			if(!$lftp && stripslashes($myREX['settings']['SELECT'][2]) == "lftp") {
				$return['method_text'] = "LFTP wurde bevorzugt, ist jedoch nicht vorhanden. SCP wird stattdessen genutzt";
				$return['final_method'] = $scp;
				$return['software'] = 'scp';
			}

			if(!$scp && stripslashes($myREX['settings']['SELECT'][2]) == "scp") {
				$return['method_text'] = "SCP wurde bevorzugt, ist jedoch nicht vorhanden. LFTP wird stattdessen genutzt";
				$return['final_method'] = $lftp;
				$return['software'] = 'lftp';
			}


		}

		return json_encode($return);
	}

	public static function push() {
		return self::execMethod();
	}


	public static function execMethod() {

		global $REX;
		$myREX = $REX['ADDON']['rex_ftpmirror'];

		$lftp = false;
		$scp = false;
		$return = array();

		$r = json_decode(self::getConnection());

		$fm = $r->final_method;
		$port = ($r->port ? ":".$r->port : "");
		$server = $r->server;
		$protocol = $r->protocol;
		$user = $r->user;
		$pass = $r->pass;
		$remotepath = $r->remotepath;
		$localpath = $r->localpath;
		$software = $r->software;
		$transfermethod = $r->transfermethod;
		$onlynew = ($r->onlynew ? "-n" : "");

		$execTrough = $r->execWith;

		$return['message'] = true;
		


		if($software == "lftp") {
			if($transfermethod == "get") {
				$return['message'] = $execTrough(''.$fm.' 2>&1 '.$protocol.'://'.$user.':'.$pass.'@'.$server.''.$port.' -e "mirror '.$onlynew.' '.$remotepath.' '.$localpath.';bye;"');
			} else {
				$return['message'] = $execTrough(''.$fm.' 2>&1 '.$protocol.'://'.$user.':'.$pass.'@'.$server.''.$port.' -e "mirror '.$onlynew.' -R '.$localpath.' '.$remotepath.';bye;"');
			}
		}


		if(!$return['message']) {
			$return['status'] = true;
		} else {
			$return['status'] = false;
		}

		return json_encode($return);

	}

}
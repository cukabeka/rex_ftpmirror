<?php
	$REX['ADDON']['install']['ftp_push_php'] = true;

	if (!rex_is_writable(dirname(__FILE__).'/logs/')) {
		$REX['ADDON']['install']['ftp_push_php'] = 0;
		$REX['ADDON']['installmsg']['installer'] = "Das Verzeichnis /logs/ im Pluginverzeichnis ftp_push_php hat keine Schreibrechte. Bitte wechsle ins Verzeichnis und setze die Schreibrechte für das Verzeichnis auf CHMOD 777";
	}
?>
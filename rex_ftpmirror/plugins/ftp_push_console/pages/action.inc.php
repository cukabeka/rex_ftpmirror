<?php
	$mode = rex_request('mode', 'string');

	if($mode == "check") {
		echo rex_ftp_push_console::checkConnection();
	}

	if($mode == "push") {
		echo rex_ftp_push_console::push();
	}
?>
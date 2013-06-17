<?php
	$mode = rex_request('mode', 'string');

	if($mode == "check") {
		echo rex_ftp_push::checkConnection();
	}

	if($mode == "push") {
		echo rex_ftp_push::push();
	}
?>
<?php

$mypage = 'ftp_push_console'; // only for this file
$parent = 'rex_ftpmirror';

$myroot = $REX['INCLUDE_PATH'].'/addons/'.$parent.'/plugins/'.$mypage.'/';

$REX['ADDON']['page'][$mypage] 		= $mypage;
$REX['ADDON']['rxid'][$mypage] 		= '823';
$REX['ADDON']['version'][$mypage] 	= '0.1';
$REX['ADDON']['author'][$mypage] 	= 'Hirbod Mirjavadi';
$REX['ADDON']['dir'][$mypage] 		= dirname(__FILE__);


	if ($REX['REDAXO']) {
		$REX['ADDON']['rex_ftpmirror_plugins'][$parent][$mypage]['subpages'][] = array('ftp_push_console', "FTP Push console");
	}
  
?>
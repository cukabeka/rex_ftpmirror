<?php

  // Name
  $addonname = 'rex_ftpmirror';
  // AUTOINSTALL THESE PLUGINS
  $autoinstall = array('ftp_push_console', 'ftp_push_php');
  $msg = '';
  
	$REX['ADDON']['install']['rex_ftpmirror'] = true;
	
	if (version_compare(PHP_VERSION, '5.0.0', '>=')) {
	} else {
		$REX['ADDON']['install']['rex_ftpmirror'] = 0;
		$REX['ADDON']['installmsg']['rex_ftpmirror'] = "Dieses Addon benötigt mindestens PHP 5.0.0 (5.3.0 empfohlen) , auf diesem System ist jedoch ".PHP_VERSION." installiert.";
	}
    	
  // GET ALL ADDONS & PLUGINS
  $all_addons = rex_read_addons_folder();
  $all_plugins = array();
  foreach($all_addons as $_addon) {
    $all_plugins[$_addon] = rex_read_plugins_folder($_addon);
  }

  // DO AUTOINSTALL
  $pluginManager = new rex_pluginManager($all_plugins, $addonname);
  foreach($autoinstall as $pluginname) {
    // INSTALL PLUGIN
    if(($instErr = $pluginManager->install($pluginname)) !== true)
    {
      $msg = $instErr;
    }

    // ACTIVATE PLUGIN
    if ($msg == '' && ($actErr = $pluginManager->activate($pluginname)) !== true)
    {
      $msg = $actErr;
    }

    if($msg != '')
    {
      break;
    }
  }

?>
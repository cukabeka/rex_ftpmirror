<?php

$mypage = 'rex_ftpmirror'; // only for this file
$myself = 'rex_ftpmirror'; // only for this file

$myroot = $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/';

$REX['ADDON']['page'][$mypage] 		= $mypage;
$REX['ADDON']['rxid'][$mypage] 		= '823';
$REX['ADDON']['version'][$mypage] 	= '1.0b';
$REX['ADDON']['author'][$mypage] 	= 'Hirbod Mirjavadi';
$REX['ADDON']['dir'][$mypage] 		= dirname(__FILE__);
//$REX['ADDON']['navigation'][$mypage] = array('block'=>'system');
$REX['ADDON']['name'][$mypage] 		= 'FTPMirror';
$REX['ADDON']['perm'][$mypage] = 'admin[]';


// Addon-Subnavigation für das REDAXO-Menue
$REX['ADDON'][$mypage]['SUBPAGES'] = array ();

// Da die Navigation des Installers anhand von Modulen generiert wird, müssen wir naträglich andocken
$REX['ADDON'][$mypage]['SUBPAGES_DOCKED'] = array (
  //     subpage    ,label                 ,perm   ,params               ,attributes
   array ('settings'         ,'Einstellungen'               ,''     ,''                   ,''),
  array ('help'         ,'Hilfe'               ,''     ,''                   ,'')
  //array ('connector','Connector (faceless subpage)',''     ,array('faceless'=>1) ,array('class'=>'jsopenwin'))
);

  // register subpages of plugins
  rex_register_extension(
    'ADDONS_INCLUDED',
    create_function(
      '',
      '
        global $REX;
        if(!empty($REX[\'ADDON\'][\'rex_ftpmirror_plugins\'][\''.$mypage.'\']))
        {
          foreach($REX[\'ADDON\'][\'rex_ftpmirror_plugins\'][\''.$mypage.'\'] as $plugin => $pluginsettings)
		  {
            if(!empty($pluginsettings[\'subpages\']))
              $REX[\'ADDON\'][\''.$mypage.'\'][\'SUBPAGES\'] = array_merge($REX[\'ADDON\'][\''.$mypage.'\'][\'SUBPAGES\'], $pluginsettings[\'subpages\']);
		  }
		  
		  $REX[\'ADDON\'][\''.$mypage.'\'][\'SUBPAGES\'] = array_merge($REX[\'ADDON\'][\''.$mypage.'\'][\'SUBPAGES\'], $REX[\'ADDON\'][\''.$mypage.'\'][\'SUBPAGES_DOCKED\']);
        }
      '
    )
  );
  
  
  // DYNAMISCHE SETTINGS
////////////////////////////////////////////////////////////////////////////////
/* dynamisch: Werte kommen aus dem "Einstellungen" Formular */
// --- DYN
$REX["ADDON"]["rex_ftpmirror"]["settings"]["TEXTINPUT"] = array (
  1 => '',
  2 => '',
  3 => '',
  4 => '',
  5 => '',
  6 => '',
  7 => '',
  8 => '',
);
$REX["ADDON"]["rex_ftpmirror"]["settings"]["SELECT"] = array (
  1 => 'ftp',
  2 => 'lftp',
  3 => 'push',
  4 => '1',
);
// --- /DYN

// HIDDEN SETTINGS
////////////////////////////////////////////////////////////////////////////////
$REX['ADDON'][$myself]['settings']['rex_list_pagination'] = 20;


if ($REX['REDAXO'] && $REX['USER'] && rex_request('page', 'string') == 'rex_ftpmirror')
{
    $pattern = $myroot.'functions/function.*.inc.php';
    $include_files = glob($pattern);
    if(is_array($include_files) && count($include_files) > 0){
      foreach ($include_files as $include)
      {
        require_once $include;
      }
    }

    $pattern = $myroot.'classes/*.class.php';
    $include_files = glob($pattern);

    if(is_array($include_files) && count($include_files) > 0){
      foreach ($include_files as $include) {
        require_once $include;
      }
    }
}


?>
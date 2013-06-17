<?php
	$mypage		= "ftp_push_console";
	 
	// Parameter
	$Basedir = dirname(__FILE__);
	
	$page		= rex_request('page', 'string');
	$subpage	= rex_request('subpage', 'string');
	$pluginpage	= rex_request('pluginpage', 'string');

	if(!$ajax)
	{
		include $REX['INCLUDE_PATH'].'/layout/top.php';
		
		
		// Build Subnavigation 
		$subpages = array(array('','FTP Push console'),);
		if(!empty($REX['ADDON']['rex_ftpmirror_plugins'][$mypage]))
		{
			foreach($REX['ADDON']['rex_ftpmirror_plugins'][$mypage] as $plugin => $pluginsettings)
			{
				if(!empty($pluginsettings['subpages']))
				{
					$subpages = array_merge($subpages, $pluginsettings['subpages']);
				}
			}
		}
		
		rex_title("FTP Mirror :: FTP Push console", $REX['ADDON'][$page]['SUBPAGES']);
	}

		
		// Include Current Page
		if(!$pluginpage)
		{
			switch($subpage)
			{
			    default:
			        require $Basedir .'/default.inc.php';
			}
		}
		
		if($pluginpage)
		{
			switch($pluginpage)
			{

			    case "action":
			    	require $Basedir .'/action.inc.php';
			    break;
			    
			    default:
			    	require $Basedir .'/default.inc.php';
			}
		}

		
		
		if(!$ajax)
		{
			// Include Footer 
			include $REX['INCLUDE_PATH'].'/layout/bottom.php';
		}		

?>
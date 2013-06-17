<?php
	$mypage		= "rex_ftpmirror";
	
	// Parameter
	$Basedir = dirname(__FILE__);
	
	$page		= rex_request('page', 'string');
	$subpage	= rex_request('subpage', 'string');
	$func		= rex_request('func', 'string');
	$ajax		= rex_request('viaAjax', 'string');
	

	$pluginssubpageloaded	= false;
	$firstPlugin			= array();
	if(!empty($REX['ADDON']['rex_ftpmirror_plugins'][$mypage]))
	{
		foreach($REX['ADDON']['rex_ftpmirror_plugins'][$mypage] as $plugin => $pluginsettings)
		{

			$firstPlugin[] .= $plugin;				

	    	if(!empty($pluginsettings['subpages']))
	    	{	   
	      		foreach($pluginsettings['subpages'] as $pluginsubpage)
	      		{
	        		if($pluginsubpage[0] == $subpage)
	        		{
	         			require $REX['ADDON']['dir'][$mypage] .'/plugins/'.$plugin.'/pages/index.inc.php';
	          			$pluginssubpageloaded = true;
	          			break;
	        		}
	      		}
	    	}
	  	}
	}


	if(!$pluginssubpageloaded)
	{
		include $REX['INCLUDE_PATH'].'/layout/top.php';
		
		
		// Build Subnavigation 
		$subpages = array();
		if(!empty($REX['ADDON']['rex_ftpmirror_plugins'][$mypage]))
		{
			foreach($REX['ADDON']['rex_ftpmirror_plugins'][$mypage] as $plugin => $pluginsettings)
			{
				if(!empty($pluginsettings['subpages']))
				{
					$subpages = array_merge($subpages, $pluginsettings['subpages']);
				}
			}
			
			$subpages = array_merge($subpages, $REX['ADDON'][$mypage]['SUBPAGES_DOCKED']);

		}
		
		
		rex_title('FTP Mirror', $subpages);
		
		if(empty($REX['ADDON']['rex_ftpmirror_plugins'][$mypage]))
		{
			echo rex_warning('Aktuell sind keine Addons oder Plugins installiert, die Inhalte für den FTP-Mirror bereitstellen.<br />Bitte klick auf Addons und <a href="?page=addon">installiere / aktiviere die Plugins</a> für den Installer');
		} else {
			if(file_exists($REX['ADDON']['dir'][$mypage] .'/pages/'.$subpage.'.inc.php')) {
				include $REX['ADDON']['dir'][$mypage] .'/pages/'.$subpage.'.inc.php';
			} else {
				header("Location: index.php?page=rex_ftpmirror&subpage=".$firstPlugin[0]."");
				exit;
			}
		}
		
		include $REX['INCLUDE_PATH'].'/layout/bottom.php';
	}

?>
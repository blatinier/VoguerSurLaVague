<?php
/*
  -------------------------------------------------------------------------
 AllMyStats V1.80 - Statistiques site web - Web traffic analysis
 -------------------------------------------------------------------------
 Copyright (C) 2008 - 2013 - Herve Seywert
 copyright-GNU-xx.txt
 -------------------------------------------------------------------------
 Web:    http://allmystats.wertronic.com - http://www.wertronic.com
 -------------------------------------------------------------------------

Note:
Pour voir les user agent no répertoriés --> Admin-->bad user agent--> Add user agent

http://www.webdevelopersnotes.com/design/list_of_browsers_for_linux_and_unix_systems.php3
http://user-agent-string.info/
http://www.whatismybrowser.com/developers/custom-parse
*/


	// ---------------- Should not be called directly -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/'.FILENAME_DISPLAY_OS_BROWSER ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

		if(isset($time_test) && $time_test == true) {
			$start = (float) array_sum(explode(' ',microtime()));  		
		}

		//###################################################################################################################
		//										Browsers
		//###################################################################################################################
		//--------------------------------- Icons Browser ----------------------------------------
		$icon_MSIE = "<img src=\"".$path_allmystats_abs."images/icons/browsers/msie.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Internet Explorer\" title=\"Internet Explorer WebTV\">";
		$icon_Netscape = "<img src=\"".$path_allmystats_abs."images/icons/browsers/netscape.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Netscape\" title=\"Netscape\">";
		$icon_FireFox = "<img src=\"".$path_allmystats_abs."images/icons/browsers/firefox.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Mozilla  Firefox\" title=\"Mozilla Firefox\">";
		$icon_Chrome = "<img src=\"".$path_allmystats_abs."images/icons/browsers/chrome.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Google chrome\" title=\"Google chrome\">";
		$icon_safari = "<img src=\"".$path_allmystats_abs."images/icons/browsers/safari.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Safari\" title=\"Safari\">";
		$icon_AOL = "<img src=\"".$path_allmystats_abs."images/icons/browsers/aol.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"AOL\" title=\"AOL\">";
		$icon_Opera = "<img src=\"".$path_allmystats_abs."images/icons/browsers/opera.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Opera\" title=\"Opera\">";
		$icon_Konqueror = "<img src=\"".$path_allmystats_abs."images/icons/browsers/konqueror.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Konqueror\" title=\"Konqueror\">";
		$icon_pdaPhone = "<img src=\"".$path_allmystats_abs."images/icons/browsers/pdaphone.png\" height=\"14\" width=\"14\" alt=\"Mobile Explorer\" title=\"Mobile Explorer\">";
		$icon_PlayStation = "<img src=\"".$path_allmystats_abs."images/icons/browsers/playstation.gif\" height=\"14\" width=\"14\" alt=\"PlayStation\" title=\"PlayStation\">";
		$icon_Nitendo = "<img src=\"".$path_allmystats_abs."images/icons/browsers/nitendo.gif\" height=\"12\" width=\"49\" alt=\"Nitendo Browser\" title=\"Nitendo Browser\">";
		$icon_Galeon = "<img src=\"".$path_allmystats_abs."images/icons/browsers/galeon.png\" height=\"14\" width=\"14\" alt=\"Galeon Browser\" title=\"Galeon Browser\">";
		$icon_Rekonq = "<img src=\"".$path_allmystats_abs."images/icons/browsers/Rekonq.png\" height=\"14\" width=\"14\" alt=\"Rekonq Web Browser\" title=\"Rekonq Web Browser\">";
		$icon_AmazonSilk = "<img src=\"".$path_allmystats_abs."images/icons/browsers/Amazon_Silk.png\" height=\"14\" width=\"14\" alt=\"Amazon Silk Web Browser\" title=\"Amazon Silk Web Browser\">";
		$icon_Lynx = '';
		// --------------------------------------------------------------------------------------

		$result = mysql_query("select agent from ".TABLE_UNIQUE_VISITOR." where date like '%".$when_date."'");
		$nbr_result = mysql_num_rows($result);

		for($i = 0; $i < $nbr_result; $i++) {
			$us_agt = mysql_result($result, $i, 'agent');

			if (strstr($us_agt, 'AOL')) { $browsers['AOL'] =  array(@++$nb_AOL, $icon_AOL, '&nbsp;AOL');    //http://www.useragentstring.com/pages/AOL/
			
			// Lynx console en ligne de commande or script
			} elseif (preg_match('/^Lynx/', $us_agt)) { $browsers['Lynx'] = array(@++$nb_Lynx, $icon_Lynx, '&nbsp;Lynx'); //http://www.useragentstring.com/pages/Lynx/						
			
			//http://www.useragentstring.com/pages/Opera/
			//http://www.opera.com/docs/history/
			} elseif (preg_match('/Opera 5\.|Opera\/5\./', $us_agt) && !strstr($us_agt, 'Version/')) { $browsers['Opera5'] = array(@++$nb_Opera5, $icon_Opera, '&nbsp;Opera v5.xx (2000/12)');
			} elseif (preg_match('/Opera 6\.|Opera\/6\./', $us_agt) && !strstr($us_agt, 'Version/')) { $browsers['Opera6'] = array(@++$nb_Opera6, $icon_Opera, '&nbsp;Opera v6.xx (2001/12)');
			} elseif (preg_match('/Opera 7\.|Opera\/7\./', $us_agt) && !strstr($us_agt, 'Version/')) { $browsers['Opera7'] = array(@++$nb_Opera7, $icon_Opera, '&nbsp;Opera v7.xx (2003/01)');
			} elseif (preg_match('/Opera 8\.|Opera\/8\./', $us_agt) && !strstr($us_agt, 'Version/')) { $browsers['Opera8'] = array(@++$nb_Opera8, $icon_Opera, '&nbsp;Opera v8.xx (2005/04)');
			} elseif (preg_match('/Opera 9\.|Opera\/9\./', $us_agt) && !strstr($us_agt, 'Opera Mini')) { $browsers['Opera9'] = array(@++$nb_Opera9, $icon_Opera, '&nbsp;Opera v9.xx (2006/06)');
			} elseif (preg_match('/Opera 10\.|Version\/10\./', $us_agt) && !strstr($us_agt, 'Opera Mini') && !strstr($us_agt, 'Opera Mobi/')) { $browsers['Opera10'] = array(@++$nb_Opera10, $icon_Opera, '&nbsp;Opera v10.xx (2010/09)');
			} elseif (preg_match('/Opera 11\.|Version\/11\./', $us_agt) && !strstr($us_agt, 'Opera Mini') && !strstr($us_agt, 'Opera Mobi/')) { $browsers['Opera11'] = array(@++$nb_Opera11, $icon_Opera, '&nbsp;Opera v11.xx (2010/12)');
			} elseif (preg_match('/Opera 12\.|Version\/12\./', $us_agt) && !strstr($us_agt, 'Opera Mini') && !strstr($us_agt, 'Opera Mobi/')) { $browsers['Opera12'] = array(@++$nb_Opera12, $icon_Opera, '&nbsp;Opera v12.xx (2012/06)');
			} elseif (preg_match('/Opera 13\.|Version\/13\./', $us_agt) && !strstr($us_agt, 'Opera Mini') && !strstr($us_agt, 'Opera Mobi/')) { $browsers['Opera13'] = array(@++$nb_Opera13, $icon_Opera, '&nbsp;Opera v13.xx');
			} elseif (preg_match('/Opera 14\.|Version\/14\./', $us_agt) && !strstr($us_agt, 'Opera Mini') && !strstr($us_agt, 'Opera Mobi/')) { $browsers['Opera14'] = array(@++$nb_Opera14, $icon_Opera, '&nbsp;Opera v14.xx');
			} elseif (preg_match('/Opera 15\.|Version\/15\./', $us_agt) && !strstr($us_agt, 'Opera Mini') && !strstr($us_agt, 'Opera Mobi/')) { $browsers['Opera15'] = array(@++$nb_Opera15, $icon_Opera, '&nbsp;Opera v15.xx');
			//Opera Mini
			//Opera/9.80 (J2ME/MIDP; Opera Mini; U; en) Presto/2.8.119 Version/11.10
			//Opera/9.80 (J2ME/MIDP; Opera Mini/5.0.3521/886; U; en) Presto/2.4.15
			} elseif (strstr($us_agt, 'Opera Mini') && ( strstr($us_agt, 'J2ME/MIDP') || strstr($us_agt, 'Android')
				|| strstr($us_agt, 'iPhone') || strstr($us_agt, 'iPad') || strstr($us_agt, 'Series 60') || strstr($us_agt, 'BlackBerry') ) ) { 
					$browsers['OperaMini'] = array(@++$nb_OperaMini, $icon_pdaPhone, '&nbsp;Opera Mini');

			//Opera Mobile
			} elseif (strstr($us_agt, 'Opera Mobi/')) { $browsers['OperaMobile'] = array(@++$nb_OperaMobile, $icon_pdaPhone, '&nbsp;Opera Mobile');
			// Others Opera
			} elseif (strstr($us_agt, 'Opera')&& !strstr($us_agt, 'Opera Mini')) { $browsers['Opera'] = array(@++$nb_Opera, $icon_Opera, '&nbsp;Opera'); 

			// Konqueror
			} elseif (strstr($us_agt, 'Konqueror')) { $browsers['Konqueror'] = array(@++$nb_Konq, $icon_Konqueror, '&nbsp;Konqueror');  //http://www.useragentstring.com/pages/Konqueror/
			
			// TODO already AppleWebKit\/53xx ??
			//[Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.34 (KHTML, like Gecko) Qt/4.8.3 Safari/534.34
			//Rekonq est un navigateur web léger basé sur QtWebKit et développé dans le cadre du projet de logiciel libre KDE Software Compilation. Il est le navigateur par défaut de Kubuntu depuis la version 10.10
			} elseif (preg_match('/AppleWebKit\/534.34|AppleWebKit\/533.3/', $us_agt) && strstr($us_agt, 'Qt/')) {  $browsers['Rekonq'] = array(@++$nb_Rekonq, $icon_Rekonq, '&nbsp;Rekonq Web Browser (for KDE Linux)');  

			//Mozilla/5.0 (Linux; U; fr-fr; KFTT Build/IML74K) AppleWebKit/535.19 (KHTML, like Gecko) Silk/2.9 Safari/535.19 Silk-Accelerated=true
			//Amazon Silk Web Browser
			} elseif (preg_match('/AppleWebKit\/535.19|AppleWebKit\/536.26/', $us_agt) && strstr($us_agt, 'Silk/')) {  $browsers['AmazonSilk'] = array(@++$nb_AmazonSilk, $icon_AmazonSilk, '&nbsp;Amazon Silk Web Browser');  

			//IE http://www.useragentstring.com/pages/Internet%20Explorer/Windows Phone
			} elseif (strstr($us_agt, 'Mozilla/4.0') && strstr($us_agt, 'WebTV')) { $browsers['MSIE4WebTV'] = array(@++$nb_MSIE4WebTV, $icon_MSIE, '&nbsp;Internet Exlorer 4 WebTV');

			//Mozilla/4.0 (compatible; MSIE 5.23; Mac_PowerPC) - Explanation: Yes MSIE does run on the MAC - here's the proof. String from Neil Thompson - thanks.
			} elseif (strstr($us_agt, 'MSIE 5.23') && strstr($us_agt, 'Mac_PowerPC') &&  !strstr($us_agt, 'AOL') && !strstr($us_agt, 'IEMobile') && !strstr($us_agt, 'Windows Phone')) { $browsers['MSIE523Mac_PowerPC'] = array(@++$nb_MSIE523Mac_PowerPC, $icon_MSIE, '&nbsp;IE on Mac OS X');

			} elseif (strstr($us_agt, 'MSIE 5.') &&  !strstr($us_agt, 'AOL') && !strstr($us_agt, 'IEMobile') && !strstr($us_agt, 'Windows Phone')) { $browsers['MSIE5'] = array(@++$nb_MSIE5, $icon_MSIE, '&nbsp;Internet Explorer 5.x (1999)');
			} elseif (strstr($us_agt, 'MSIE 6.0') && !strstr($us_agt, 'AOL') && !strstr($us_agt, 'IEMobile') && !strstr($us_agt, 'Windows Phone')) { $browsers['MSIE6'] = array(@++$nb_MSIE6, $icon_MSIE, '&nbsp;Internet Explorer 6.0 (2001)');
			} elseif (strstr($us_agt, 'MSIE 7.0') && !strstr($us_agt, 'AOL') && !strstr($us_agt, 'IEMobile') && !strstr($us_agt, 'Windows Phone')) { $browsers['MSIE7'] = array(@++$nb_MSIE7, $icon_MSIE, '&nbsp;Internet Explorer 7.0 (2006)');
			} elseif (strstr($us_agt, 'MSIE 8.0') && !strstr($us_agt, 'AOL') && !strstr($us_agt, 'IEMobile') && !strstr($us_agt, 'Windows Phone')) { $browsers['MSIE8'] = array(@++$nb_MSIE8, $icon_MSIE, '&nbsp;Internet Explorer 8.0 (2008)');
			} elseif (strstr($us_agt, 'MSIE 9.0') && !strstr($us_agt, 'AOL') && !strstr($us_agt, 'IEMobile') && !strstr($us_agt, 'Windows Phone')) { $browsers['MSIE9'] = array(@++$nb_MSIE9, $icon_MSIE, '&nbsp;Internet Explorer 9.0 (2011)');
			} elseif (strstr($us_agt, 'MSIE 10.0') && !strstr($us_agt, 'AOL') && !strstr($us_agt, 'IEMobile') && !strstr($us_agt, 'Windows Phone')) { $browsers['MSIE10'] = array(@++$nb_MSIE9, $icon_MSIE, '&nbsp;Internet Explorer 10.0 (2012)');

			//IE Browser MOBILE (Microsoft PocketPC device and a Microsoft smartphone)
			} elseif (strstr($us_agt, 'MSIE 3.02'))  { $browsers['MSIE302'] = array(@++$nb_MSIE302, $icon_MSIE, '&nbsp;Microsoft Mobile Explorer'); //Mozilla/2.0 (compatible; MSIE 3.02; Windows CE; 240x320)	
			// Microsoft Word Browser
			} elseif (strstr($us_agt, 'Word/'))  { $browsers['Word'] = array(@++$nb_Word, $icon_MSIE, '&nbsp;Microsoft Word Browser'); 
			// Suite internet de Mozilla, nommée SeaMonkey (2006)
			} elseif (strstr($us_agt, 'SeaMonkey/'))  { $browsers['SeaMonkey'] = array(@++$nb_SeaMonkey, '', '&nbsp;Suite internet de Mozilla, nommée SeaMonkey (2006)'); 
			// Google Wireless Transcoder
			} elseif (strstr($us_agt, 'Google Wireless Transcoder'))  { $browsers['GoogleWireless'] = array(@++$nb_GoogleWireless, $icon_Chrome, '&nbsp;Google Wireless'); 

			} else {
				//Google Chrome
				if (strstr($us_agt, 'Chrome') && strstr($us_agt, 'Safari')) { 
					if(strstr($us_agt, 'Chrome/0.')) { $browsers['GChromev0'] = array(@++$nb_GChromev0, $icon_Chrome, '&nbsp;Google Chrome v0.x');
					} elseif (strstr($us_agt, 'Chrome/1.')) { $browsers['GChromev1'] = array(@++$nb_GChromev1, $icon_Chrome, '&nbsp;Google Chrome v1.x');
					} elseif (strstr($us_agt, 'Chrome/2.')) { $browsers['GChromev2'] = array(@++$nb_GChromev2, $icon_Chrome, '&nbsp;Google Chrome v2.x');
					} elseif (strstr($us_agt, 'Chrome/3.')) { $browsers['GChromev3'] = array(@++$nb_GChromev3, $icon_Chrome, '&nbsp;Google Chrome v3.x');
					} elseif (strstr($us_agt, 'Chrome/4.')) { $browsers['GChromev4'] = array(@++$nb_GChromev4, $icon_Chrome, '&nbsp;Google Chrome v4.x');
					} elseif (strstr($us_agt, 'Chrome/5.')) { $browsers['GChromev5'] = array(@++$nb_GChromev5, $icon_Chrome, '&nbsp;Google Chrome v5.x');
					} elseif (strstr($us_agt, 'Chrome/6.')) { $browsers['GChromev6'] = array(@++$nb_GChromev6, $icon_Chrome, '&nbsp;Google Chrome v6.x');
					} elseif (strstr($us_agt, 'Chrome/7.')) { $browsers['GChromev7'] = array(@++$nb_GChromev7, $icon_Chrome, '&nbsp;Google Chrome v7.x');
					} elseif (strstr($us_agt, 'Chrome/8.')) { $browsers['GChromev8'] = array(@++$nb_GChromev8, $icon_Chrome, '&nbsp;Google Chrome v8.x');
					} elseif (strstr($us_agt, 'Chrome/9.')) { $browsers['GChromev9'] = array(@++$nb_GChromev9, $icon_Chrome, '&nbsp;Google Chrome v9.x');
					} elseif (strstr($us_agt, 'Chrome/10.')) { $browsers['GChromev10'] = array(@++$nb_GChromev10, $icon_Chrome, '&nbsp;Google Chrome v10.x');
					} elseif (strstr($us_agt, 'Chrome/11.')) { $browsers['GChromev11'] = array(@++$nb_GChromev11, $icon_Chrome, '&nbsp;Google Chrome v11.x');
					} elseif (strstr($us_agt, 'Chrome/12.')) { $browsers['GChromev12'] = array(@++$nb_GChromev12, $icon_Chrome, '&nbsp;Google Chrome v12.x');
					} elseif (strstr($us_agt, 'Chrome/13.')) { $browsers['GChromev13'] = array(@++$nb_GChromev13, $icon_Chrome, '&nbsp;Google Chrome v13.x');
					} elseif (strstr($us_agt, 'Chrome/14.')) { $browsers['GChromev14'] = array(@++$nb_GChromev14, $icon_Chrome, '&nbsp;Google Chrome v14.x');
					} elseif (strstr($us_agt, 'Chrome/15.')) { $browsers['GChromev15'] = array(@++$nb_GChromev15, $icon_Chrome, '&nbsp;Google Chrome v15.x');
					} elseif (strstr($us_agt, 'Chrome/16.')) { $browsers['GChromev16'] = array(@++$nb_GChromev16, $icon_Chrome, '&nbsp;Google Chrome v16.x');
					} elseif (strstr($us_agt, 'Chrome/17.')) { $browsers['GChromev17'] = array(@++$nb_GChromev17, $icon_Chrome, '&nbsp;Google Chrome v17.x');
					} elseif (strstr($us_agt, 'Chrome/18.')) { $browsers['GChromev18'] = array(@++$nb_GChromev18, $icon_Chrome, '&nbsp;Google Chrome v18.x');
					} elseif (strstr($us_agt, 'Chrome/19.')) { $browsers['GChromev19'] = array(@++$nb_GChromev19, $icon_Chrome, '&nbsp;Google Chrome v19.x');
					} elseif (strstr($us_agt, 'Chrome/20.')) { $browsers['GChromev20'] = array(@++$nb_GChromev20, $icon_Chrome, '&nbsp;Google Chrome v20.x');
					} elseif (strstr($us_agt, 'Chrome/21.')) { $browsers['GChromev21'] = array(@++$nb_GChromev21, $icon_Chrome, '&nbsp;Google Chrome v21.x');
					} elseif (strstr($us_agt, 'Chrome/22.')) { $browsers['GChromev22'] = array(@++$nb_GChromev22, $icon_Chrome, '&nbsp;Google Chrome v22.x');
					} elseif (strstr($us_agt, 'Chrome/23.')) { $browsers['GChromev23'] = array(@++$nb_GChromev23, $icon_Chrome, '&nbsp;Google Chrome v23.x');
					} elseif (strstr($us_agt, 'Chrome/24.')) { $browsers['GChromev24'] = array(@++$nb_GChromev24, $icon_Chrome, '&nbsp;Google Chrome v24.x');
					} elseif (strstr($us_agt, 'Chrome/25.')) { $browsers['GChromev25'] = array(@++$nb_GChromev25, $icon_Chrome, '&nbsp;Google Chrome v25.x');
					} elseif (strstr($us_agt, 'Chrome/26.')) { $browsers['GChromev26'] = array(@++$nb_GChromev26, $icon_Chrome, '&nbsp;Google Chrome v26.x');
					} elseif (strstr($us_agt, 'Chrome/27.')) { $browsers['GChromev27'] = array(@++$nb_GChromev27, $icon_Chrome, '&nbsp;Google Chrome v27.x');
					} elseif (strstr($us_agt, 'Chrome/28.')) { $browsers['GChromev28'] = array(@++$nb_GChromev28, $icon_Chrome, '&nbsp;Google Chrome v28.x');
					} elseif (strstr($us_agt, 'Chrome/29.')) { $browsers['GChromev29'] = array(@++$nb_GChromev29, $icon_Chrome, '&nbsp;Google Chrome v29.x');
					} elseif (strstr($us_agt, 'Chrome/30.')) { $browsers['GChromev30'] = array(@++$nb_GChromev30, $icon_Chrome, '&nbsp;Google Chrome v30.x');
					} elseif (strstr($us_agt, 'Chrome/31.')) { $browsers['GChromev31'] = array(@++$nb_GChromev31, $icon_Chrome, '&nbsp;Google Chrome v31.x');
					} elseif (strstr($us_agt, 'Chrome/32.')) { $browsers['GChromev32'] = array(@++$nb_GChromev32, $icon_Chrome, '&nbsp;Google Chrome v32.x');
					} elseif (strstr($us_agt, 'Chrome/33.')) { $browsers['GChromev33'] = array(@++$nb_GChromev33, $icon_Chrome, '&nbsp;Google Chrome v33.x');
					} elseif (strstr($us_agt, 'Chrome/34.')) { $browsers['GChromev34'] = array(@++$nb_GChromev34, $icon_Chrome, '&nbsp;Google Chrome v34.x');
					} elseif (strstr($us_agt, 'Chrome/35.')) { $browsers['GChromev35'] = array(@++$nb_GChromev35, $icon_Chrome, '&nbsp;Google Chrome v35.x');
					} else { 
						$browsers['GChromeVunknown'] = array(@++$nb_GChromeVunknown, $icon_Chrome, '&nbsp;Google Chrome unknown Ver');
					}
				} elseif (strstr($us_agt, 'Netscape') ) { //http://www.useragentstring.com/pages/Netscape/
					if (strstr($us_agt, 'Netscape6/6.1')) { $browsers['Netscape61'] = array(@++$nb_Netscape61, $icon_Netscape, '&nbsp;Netscape 6.1');
					} elseif (strstr($us_agt, 'Netscape6/6.2')) { $browsers['Netscape62'] = array(@++$nb_Netscape62, $icon_Netscape, '&nbsp;Netscape 6.2');
					} elseif (strstr($us_agt, 'Netscape6/7.')) { $browsers['Netscape7'] = array(@++$nb_Netscape7, $icon_Netscape, '&nbsp;Netscape 7.x');
					} elseif (strstr($us_agt, 'Netscape6/8.')) { $browsers['Netscape8'] = array(@++$nb_Netscape8, $icon_Netscape, '&nbsp;Netscape 8.x');
					} elseif (strstr($us_agt, 'Navigator/9.')) { $browsers['Netscape9'] = array(@++$nb_Netscape9, $icon_Netscape, '&nbsp;Netscape 9.x');							
					} elseif (strstr($us_agt, 'Navigator/9.0b2.')) { $browsers['Netscape90b2'] = array(@++$nb_Netscape90b2, $icon_Netscape, '&nbsp;Netscape 9.0b2');
					} elseif (strstr($us_agt, 'Navigator/9.0b3.')) { $browsers['Netscape90b3'] = array(@++$nb_Netscape90b3, $icon_Netscape, '&nbsp;Netscape 9.0b3');
					} elseif (strstr($us_agt, 'Navigator/9.0RC1.')) { $browsers['Netscape90RC1'] = array(@++$nb_Netscape90RC1, $icon_Netscape, '&nbsp;Netscape 9.0RC1');
					} else { 
						$browsers['NetscapeVunknown'] = array(@++$nb_NetscapeVunknown, $icon_Netscape, '&nbsp;Netscape unknown ver');
					}
				//Nescape 0 to 4 (old)
				} elseif (preg_match('/^Mozilla\/[0-4]\.[0-8][0-9][ ]\[(en|en-US|en-gb|fr|de|it|pl|nl|es|ja|fi)\]|^Mozilla\/([0-4]\.[0-8][0-9][ ])\((Macintosh; U; PPC|Macintosh; I; PPC)\)/', $us_agt)) {
						if ( strstr($us_agt, 'Mozilla/2.') && strstr($us_agt, 'fr') ) { 
							$browsers['Netscape2'] = array(@++$nb_Netscape2, $icon_Netscape, '&nbsp;Netscape 2.x');
						} elseif (strstr($us_agt, 'Mozilla/3.')) { 
							$browsers['Netscape3'] = array(@++$nb_Netscape3, $icon_Netscape, '&nbsp;Netscape 3.x');
						} elseif (strstr($us_agt, 'Mozilla/4.')) { 
							$browsers['Netscape4'] = array(@++$nb_Netscape4, $icon_Netscape, '&nbsp;Netscape 4.x');
						} else { 
							$browsers['NetscapeVunknown'] = array(@++$nb_NetscapeVunknown, $icon_Netscape, '&nbsp;Netscape unknown ver');
						}
				//Firefox http://www.useragentstring.com/pages/Firefox/
				} elseif (strstr($us_agt, 'Firefox')) {
					if(strstr($us_agt, 'Firefox/0.')) { $browsers['Firefox0'] = array(@++$nb_Firefox0, $icon_FireFox, '&nbsp;Mozilla Firefox v0.x (2002/09 --> 2004/10)');
					} elseif (strstr($us_agt, 'Firefox/1.')) { $browsers['Firefox1'] = array(@++$nb_Firefox1, $icon_FireFox, '&nbsp;Mozilla Firefox 1.x (2005/02 --> 2007/05)');
					} elseif (strstr($us_agt, 'Firefox/2.')) { $browsers['Firefox2'] = array(@++$nb_Firefox2, $icon_FireFox, '&nbsp;Mozilla Firefox 2.x (2006/10 --> 2008/12)');
					} elseif (strstr($us_agt, 'Firefox/3.')) { $browsers['Firefox3'] = array(@++$nb_Firefox3, $icon_FireFox, '&nbsp;Mozilla Firefox 3.x (2008/07 --> 2012/03)');
					} elseif (strstr($us_agt, 'Firefox/4.')) { $browsers['Firefox4'] = array(@++$nb_Firefox4, $icon_FireFox, '&nbsp;Mozilla Firefox 4.x (2011/03)');
					} elseif (strstr($us_agt, 'Firefox/5.')) { $browsers['Firefox5'] = array(@++$nb_Firefox5, $icon_FireFox, '&nbsp;Mozilla Firefox 5.x (2011/06)');
					} elseif (strstr($us_agt, 'Firefox/6.')) { $browsers['Firefox6'] = array(@++$nb_Firefox6, $icon_FireFox, '&nbsp;Mozilla Firefox 6.x (2011/08)');						
					} elseif (strstr($us_agt, 'Firefox/7.')) { $browsers['Firefox7'] = array(@++$nb_Firefox7, $icon_FireFox, '&nbsp;Mozilla Firefox 7.x (2011/09)');
					} elseif (strstr($us_agt, 'Firefox/8.')) { $browsers['Firefox8'] = array(@++$nb_Firefox8, $icon_FireFox, '&nbsp;Mozilla Firefox 8.x (2011/11)');
					} elseif (strstr($us_agt, 'Firefox/9.')) { $browsers['Firefox9'] = array(@++$nb_Firefox9, $icon_FireFox, '&nbsp;Mozilla Firefox 9.x (2011/12)');						
					} elseif (strstr($us_agt, 'Firefox/10.')) { $browsers['Firefox10'] = array(@++$nb_Firefox10, $icon_FireFox, '&nbsp;Mozilla Firefox 10.x 2012/02)');						
					} elseif (strstr($us_agt, 'Firefox/11.')) { $browsers['Firefox11'] = array(@++$nb_Firefox11, $icon_FireFox, '&nbsp;Mozilla Firefox 11.x (2012/03)');
					} elseif (strstr($us_agt, 'Firefox/12.')) { $browsers['Firefox12'] = array(@++$nb_Firefox12, $icon_FireFox, '&nbsp;Mozilla Firefox 12.x (2012/04)');
					} elseif (strstr($us_agt, 'Firefox/13.')) { $browsers['Firefox13'] = array(@++$nb_Firefox13, $icon_FireFox, '&nbsp;Mozilla Firefox 13.x (2012/06)');						
					} elseif (strstr($us_agt, 'Firefox/14.')) { $browsers['Firefox14'] = array(@++$nb_Firefox14, $icon_FireFox, '&nbsp;Mozilla Firefox 14.x (2012/07)');						
					} elseif (strstr($us_agt, 'Firefox/15.')) { $browsers['Firefox15'] = array(@++$nb_Firefox15, $icon_FireFox, '&nbsp;Mozilla Firefox 15.x (2012/08)');
					} elseif (strstr($us_agt, 'Firefox/16.')) { $browsers['Firefox16'] = array(@++$nb_Firefox16, $icon_FireFox, '&nbsp;Mozilla Firefox 16.x (2012/10)');
					} elseif (strstr($us_agt, 'Firefox/17.')) { $browsers['Firefox17'] = array(@++$nb_Firefox17, $icon_FireFox, '&nbsp;Mozilla Firefox 17.x (2012/11)');						
					} elseif (strstr($us_agt, 'Firefox/18.')) { $browsers['Firefox18'] = array(@++$nb_Firefox18, $icon_FireFox, '&nbsp;Mozilla Firefox 18.x (2013/01)');
					} elseif (strstr($us_agt, 'Firefox/19.')) { $browsers['Firefox19'] = array(@++$nb_Firefox19, $icon_FireFox, '&nbsp;Mozilla Firefox 19.x (2013/02)');
					} elseif (strstr($us_agt, 'Firefox/20.')) { $browsers['Firefox20'] = array(@++$nb_Firefox20, $icon_FireFox, '&nbsp;Mozilla Firefox 20.x (2013/04)');						
					} elseif (strstr($us_agt, 'Firefox/21.')) { $browsers['Firefox21'] = array(@++$nb_Firefox21, $icon_FireFox, '&nbsp;Mozilla Firefox 21.x');
					} elseif (strstr($us_agt, 'Firefox/22.')) { $browsers['Firefox22'] = array(@++$nb_Firefox22, $icon_FireFox, '&nbsp;Mozilla Firefox 22.x');						
					} elseif (strstr($us_agt, 'Firefox/23.')) { $browsers['Firefox23'] = array(@++$nb_Firefox23, $icon_FireFox, '&nbsp;Mozilla Firefox 23.x');
					} elseif (strstr($us_agt, 'Firefox/24.')) { $browsers['Firefox24'] = array(@++$nb_Firefox24, $icon_FireFox, '&nbsp;Mozilla Firefox 24.x');
					} elseif (strstr($us_agt, 'Firefox/25.')) { $browsers['Firefox25'] = array(@++$nb_Firefox25, $icon_FireFox, '&nbsp;Mozilla Firefox 25.x');						
					} else {
						$browsers['FirefoxVunknown'] = array(@++$nb_FirefoxVunknown, $icon_FireFox, '&nbsp;Firefox unknown ver');
					} 
				
				} elseif (strstr($us_agt, 'Galeon/')) {	$browsers['Galeon'] = array(@++$nb_Galeon, $icon_Galeon, '&nbsp;Galeon Web browser (for Gnome Linux)');
							
				// ----------- Mobile browser -------------------------------
				// 2012-07-15 - correction  - Mis avant la detection Safari seule car user agent safari peut être un mobile
				} elseif (strstr($us_agt, 'iPhone') && ( strstr($us_agt, 'Safari') || strstr($us_agt, 'like Mac OS X'))) { $browsers['iPhoneSafari'] = array(@++$nb_iPhoneSafari, $icon_pdaPhone, '&nbsp;iPhone Safari');
				} elseif (strstr($us_agt, 'iPad') && ( strstr($us_agt, 'Safari') || strstr($us_agt, 'like Mac OS X'))) { $browsers['iPadSafari'] = array(@++$nb_iPadSafari, $icon_pdaPhone, '&nbsp;iPad Safari');
				} elseif (strstr($us_agt, 'iPod') && ( strstr($us_agt, 'Safari') || strstr($us_agt, 'like Mac OS X'))) { $browsers['iPodSafari'] = array(@++$nb_iPodSafari, $icon_pdaPhone, '&nbsp;iPod Safari');

				} elseif (strstr($us_agt, 'Nokia') && strstr($us_agt, 'Opera')) { $browsers['NokiaOpera'] = array(@++$nb_NokiaOpera, $icon_pdaPhone, '&nbsp;Nokia Opera');
				} elseif (strstr($us_agt, 'NokiaBrowser/') && strstr($us_agt, 'Mobile Safari')) { $browsers['NokiaBrowser'] = array(@++$nb_NokiaBrowser, $icon_pdaPhone, '&nbsp;Nokia Browser (PDA/Phone browser)');
				} elseif (strstr($us_agt, 'Nokia') && strstr($us_agt, 'BrowserNG')) { $browsers['NokiaBrowser'] = array(@++$nb_NokiaBrowser, $icon_pdaPhone, '&nbsp;Nokia Browser (PDA/Phone browser)');
				} elseif (strstr($us_agt, 'Nokia') && strstr($us_agt, 'S40OviBrowser/')) { $browsers['S40OviBrowser/'] = array(@++$nb_S40OviBrowser, $icon_pdaPhone, '&nbsp;Nokia IBrowse');
				} elseif (strstr($us_agt, 'Nokia')) { $browsers['Nokia'] = array(@++$nb_Nokia, $icon_pdaPhone, '&nbsp;Nokia');

				//BlackBerry - (Mozilla/5.0 (BlackBerry; U; BlackBerry 9900; en) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.1.0.346 Mobile Safari/534.11+)
				} elseif (strstr($us_agt, 'BlackBerry ') && strstr($us_agt, ' AppleWebKit')) { $browsers['BlackBerry'] = array(@++$nb_BlackBerry, $icon_pdaPhone, '&nbsp;BlackBerry');
				//} elseif (strstr($us_agt, 'BlackBerry; U; BlackBerry ') && strstr($us_agt, ' AppleWebKit')) { $browsers['BlackBerry'] = array(@++$nb_BlackBerry, $icon_pdaPhone, '&nbsp;BlackBerry');

				//Android Webkit Browser - Mozilla/5.0 (Linux; U; Android 4.0.3; ko-kr; LG-L160L Build/IML74K) AppleWebkit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30
				} elseif (strstr($us_agt, 'Linux; U; Android ')) { $browsers['AndroidWebkitBrowser'] = array(@++$nb_AndroidWebkitBrowser, $icon_pdaPhone, '&nbsp;Android Webkit Browser');
				
				//IE Mobile 10.0 - Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; HTC; Windows Phone 8X by HTC)
				} elseif (strstr($us_agt, 'MSIE 10.0') && strstr($us_agt, 'Windows Phone 8.0') ) { $browsers['IEMobile10'] = array(@++$nb_IEMobile10, $icon_pdaPhone, '&nbsp;IE Mobile 10.0');
				//IE Mobile 9.0 - Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0)
				} elseif (strstr($us_agt, 'MSIE 9.0') && strstr($us_agt, 'Windows Phone OS 7.5') ) { $browsers['IEMobile9'] = array(@++$nb_IEMobile9, $icon_pdaPhone, '&nbsp;IE Mobile 9.0');
				//IE Mobile 7.11 - HTC_Touch_3G Mozilla/4.0 (compatible; MSIE 6.0; Windows CE; IEMobile 7.11)
				} elseif (strstr($us_agt, 'MSIE 6.0') && strstr($us_agt, 'IEMobile 7.11') ) { $browsers['IEMobile711'] = array(@++$nb_IEMobile711, $icon_pdaPhone, '&nbsp;IE Mobile 7.11');
				//IE Mobile 7.0 - Mozilla/4.0 (compatible; MSIE 7.0; Windows Phone OS 7.0; Trident/3.1; IEMobile/7.0; Nokia;N70)
				} elseif (strstr($us_agt, 'MSIE 7.0') && strstr($us_agt, 'Phone OS 7.0') ) { $browsers['IEMobile7'] = array(@++$nb_IEMobile7, $icon_pdaPhone, '&nbsp;IE Mobile 7.0');

				} elseif (strstr($us_agt, 'LG ') && preg_match('/lg-|LG\/|LGE/', $us_agt)) {  $browsers['LGElectronics'] = array(@++$nb_LGElectronics, $icon_pdaPhone, '&nbsp;LG Electronics');
				} elseif (strstr($us_agt, 'Motorola ')) { $browsers['Motorola'] = array(@++$nb_Motorola, $icon_pdaPhone, '&nbsp;Motorola');

				} elseif (strstr($us_agt, 'SAMSUNG-SGH') && preg_match('/NetFront\/|Browser/', $us_agt)) { $browsers['SAMSUNGNetfront'] = array(@++$nb_SSAMSUNGNetfront, $icon_pdaPhone, '&nbsp;Samsung NetFront');
				} elseif (strstr($us_agt, 'SAMSUNG-GT') && preg_match('/Dolfin/', $us_agt)) { $browsers['Dolfin'] = array(@++$nb_SSAMSUNGNetfront, $icon_pdaPhone, '&nbsp;Samsung Dolfin');

				} elseif (strstr($us_agt, 'SonyEricsson') && strstr($us_agt, 'NetFront/')) { $browsers['SonyEricssonNetFront'] = array(@++$nb_SonyEricssonNetFront, $icon_pdaPhone, '&nbsp;Sony/Ericsson NetFront');
				} elseif ((strstr($us_agt, 'webOS') && strstr($us_agt, 'Safari')) || (strstr($us_agt, 'Windows 98') && strstr($us_agt, 'PalmSource')) ) { $browsers['HPPalm'] = array(@++$nb_HPPalm, $icon_pdaPhone, '&nbsp;HP Palm');

				// PLAYSTATION 3 4.20), NintendoBrowser
				} elseif (strstr($us_agt, 'NintendoBrowser/')) { $browsers['NintendoBrowser'] = array(@++$nb_NintendoBrowser, $icon_Nitendo, '&nbsp;Nintendo Browser');

				//----------------------------------------------------------
				//Safari http://www.useragentstring.com/pages/Safari/
				} elseif (preg_match('/Safari\/485|Safari\/125|Safari\/312|Safari\/100/', $us_agt)) { $browsers['Safari1'] = array(@++$nb_Safari1, $icon_safari, '&nbsp;Safari 1.x');
				} elseif (preg_match("/Safari\/412|Safari\/416|Safari\/417|Safari\/419/", $us_agt)) { $browsers['Safari2'] = array(@++$nb_Safari2, $icon_safari, '&nbsp;Safari 2.x');
				} elseif (strstr($us_agt, 'Safari/') && strstr($us_agt, 'Version/3.')) { $browsers['Safari3'] = array(@++$nb_Safari3, $icon_safari, '&nbsp;Safari 3.x');
				} elseif (strstr($us_agt, 'Safari/') && strstr($us_agt, 'Version/4.')) { $browsers['Safari4'] = array(@++$nb_Safari4, $icon_safari, '&nbsp;Safari 4.x (2009/04)');
				} elseif (strstr($us_agt, 'Safari/') && strstr($us_agt, 'Version/5.')) { $browsers['Safari5'] = array(@++$nb_Safari5, $icon_safari, '&nbsp;Safari 5.x (2010/06 --> 2011/07)');
				} elseif (strstr($us_agt, 'Safari/') && strstr($us_agt, 'Version/6.')) { $browsers['Safari6'] = array(@++$nb_Safari6, $icon_safari, '&nbsp;Safari 6.x (2012/08)');
				} elseif (strstr($us_agt, 'Safari/') && strstr($us_agt, 'Version/7.')) { $browsers['Safari7'] = array(@++$nb_Safari7, $icon_safari, '&nbsp;Safari 7.x');
				
				} elseif (strstr($us_agt, 'Safari/') && strstr($us_agt, 'Version/10.')) { $browsers['Safari10'] = array(@++$nb_Safari10, $icon_safari, '&nbsp;Safari 10.x');

				// Mobile
				} elseif ( strstr($us_agt, 'MobileSafari/') && preg_match('/Darwin/', $us_agt) ) { $OpSys['SafariMobileDarwin'] = array(@++$nb_SafariMobileDarwin, $icon_Mac, '&nbsp;Safari Mobile Darwin'); //For Apple iPhone, iPad, iPod touch


				//Powermarks see http://www.kaylon.com/
				} elseif (strstr($us_agt, 'Powermarks/')) { $browsers['Powermarks'] = array(@++$nb_Powermarks, '', '&nbsp;Powermarks');

				//Browsers Others or unknown or Not recognized by AllMyStats
				} else { 
					if (trim($us_agt)<>''){
						$Other_browsers_os_bots[] = $us_agt;
						$Other_type[] = 'Browser Not recognized';
					}
				}
			}					

			//###################################################################################################################
							//OPERATING SYSTEM
			//--------------------------------- Icons OS ----------------------------------------
			$icon_Win_2 = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/win_2.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Microsoft Windows\" title=\"Microsoft Windows\">";
			$icon_vista = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/win_vista.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Microsoft Windows\" title=\"Microsoft Windows\">";
			$icon_Win = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/win.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Microsoft Windows\" title=\"Microsoft Windows\">";
			$icon_Mac = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/mac.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Macintosh\" title=\"Macintosh\">";
			$icon_Mac_mobile = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/mac_mob.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Macintosh mobile\" title=\"Macintosh mobile\">";
			$icon_Linux = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/linux.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"linux\" title=\"linux\">";
			$icon_FreeBSD = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/freebsd.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"FreeBsd\" title=\"FreeBsd\">";
			$icon_Unix = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/unix.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Unix\" title=\"Unix\">";
			$icon_Solaris = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/solaris.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"SunOS - Solaris\" title=\"SunOS - Solaris\">";
			$icon_BeOS = '';
			$icon_OS_2 = '';
			$icon_Aix = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/aix.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"AIX\" title=\"AIX\">";
			$icon_SymbianOS = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/symbian_os.gif\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Symbian OS\" title=\"Symbian OS\">";
			$icon_AndroidOS = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/android.gif\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Androïd OS mobile de Google\" title=\"Androïd OS mobile de Google\">";
			$icon_HPPalm = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/hp_palm.gif\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"WebOS HP Palm\" title=\"WebOS HP Palm\">";
			$icon_BlackBerryOS = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/Black-berry.gif\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"BlackBerry OS\" title=\"BlackBerry OS\">";
			$icon_J2ME_MIDP = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/J2ME_MIDP.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"BlackBerry OS\" title=\"BlackBerry OS\">";
			//----------------------------------------------------------------------------------

			if(strstr($us_agt, 'Windows NT 5.0')) { $OpSys['Windows2000'] = array(@++$nb_Windows2000, $icon_Win, '&nbsp;Windows 2000'); 
			} elseif (strstr($us_agt, 'Windows NT 5.1')) { $OpSys['WindowsXP'] = array(@++$nb_WindowsXP, $icon_Win_2, '&nbsp;Windows XP');
			} elseif (strstr($us_agt, 'Windows NT 5.2')){ $OpSys['WindowsServer2003'] = array(@++$nb_WindowsServer2003, $icon_Win_2, '&nbsp;Windows Server 2003'); 
			} elseif (strstr($us_agt, 'Windows NT 6.0')) { $OpSys['WindowsVista'] = array(@++$nb_WindowsVista, $icon_vista, '&nbsp;Windows Vista');
			} elseif (strstr($us_agt, 'Windows NT 6.1')) { $OpSys['Windows7'] = array(@++$nb_Windows7, $icon_Win_2, '&nbsp;Windows 7');
			} elseif (strstr($us_agt, 'Windows NT 6.2')) { $OpSys['Windows8'] = array(@++$nb_WindowsVista, $icon_vista, '&nbsp;Windows 8');  

			// Windows NT
			} elseif (strstr($us_agt, 'Windows NT 4'))  { $OpSys['WindowsNT'] = array(@++$nb_WindowsNT, $icon_Win, '&nbsp;Windows NT');
			} elseif (strstr($us_agt, 'Windows NT') && strstr($us_agt, 'DigExt'))  {  $OpSys['WindowsNT'] =  array(@++$nb_WindowsNT, $icon_Win, '&nbsp;Windows NT');
			
			} elseif (preg_match('/Windows 98|Win98|Windows ME|Win 9x 4\.90/', $us_agt)) { $OpSys['Windows98'] = array(@++$nb_Windows98, $icon_Win, '&nbsp;Windows 98');
			
			} elseif (strstr($us_agt, 'Windows 95')) { $OpSys['Windows95'] = array(@++$nb_Windows95, $icon_Win, '&nbsp;Windows 95');
			
			//------------ Mobiles OS ---------------------
			/*
			Windows Mobile est le nom générique donné à différentes versions de Microsoft Windows conçues pour des appareils mobiles 
			tels que les smartphones (téléphones intelligent, en français) ou Pocket PC.
			
			iOS, anciennement iPhone OS, est le système d'exploitation mobile développé par Apple pour l'iPhone, l'iPod touch, et l'iPad. 
			Il est dérivé de Mac OS X dont il partage les fondations (le kernel hybride XNU basé sur le micro-noyau Mach, les services Unix et Cocoa, etc.)
			
			BlackBerry est une ligne de téléphones intelligents développée depuis 19991 par la compagnie canadienne Research In Motion (RIM), 
			utilisant le système d'exploitation propriétaire Blackberry OS.
			*/

			//device_os	Windows Mobile OS
			} elseif (strstr($us_agt, 'Windows CE')) { $OpSys['WindowsCE'] = array(@++$nb_WindowsCE, $icon_Win_2, '&nbsp;Windows CE');
			} elseif (strstr($us_agt, 'Microsoft Windows; PPC;')) { $OpSys['MicrosoftWindowsPPC'] = array(@++$nb_MicrosoftWindowsPPC, $icon_Win_2, '&nbsp;Windows PPC');
			} elseif (strstr($us_agt, 'MSIE 7.0') && strstr($us_agt, 'Phone OS 7.0') ) { $OpSys['WindowsPhoneOS70'] = array(@++$nb_WindowsPhoneOS70, $icon_Win_2, '&nbsp;Windows Phone OS 7.0');
			} elseif (strstr($us_agt, 'Windows Phone OS 7.5')) { $OpSys['WindowsPhoneOS75'] = array(@++$nb_WindowsPhoneOS75, $icon_Win_2, '&nbsp;Windows Phone OS 7.5');
			} elseif (strstr($us_agt, 'Windows Phone 8.0')) { $OpSys['WindowsPhoneOS80'] = array(@++$nb_WindowsPhoneOS80, $icon_Win_2, '&nbsp;Windows Phone OS 8.0');

			// 2012-07-15 - correction - Mis avant la detection Mac OS seule
			//Mac OS Device
			} elseif ( strstr($us_agt, 'Mac OS') && preg_match('/iPhone|iPad|iPod/', $us_agt) ) { $OpSys['MacOSMobile'] = array(@++$nb_MacOSMobile, $icon_Mac, '&nbsp;Mac OS Mobile'); //For Apple iPhone, iPad, iPod touch
			} elseif ( strstr($us_agt, 'MobileSafari/') && preg_match('/Darwin/', $us_agt) ) { $OpSys['MacOSMobileDarwin'] = array(@++$nb_MacOSMobile, $icon_Mac, '&nbsp;Mac OS Darwin'); //For Apple iPhone, iPad, iPod touch

			//Iphone OS (Mac)
			} elseif ( strstr($us_agt, 'iPhone OS') ) { $OpSys['iPhoneOS'] = array(@++$nb_iPhoneOS, $icon_Mac, '&nbsp;iPhone OS');   //For Apple iPhone, iPad, iPod touch
			//Symbian OS
			} elseif ( strstr($us_agt, 'SymbianOS') || strstr($us_agt, 'Symbian OS') || strstr($us_agt, 'SymbOS')) { $OpSys['SymbianOS'] = array(@++$nb_SymbianOS, $icon_SymbianOS, '&nbsp;Symbian OS Nokia'); //For OS for Nokia - Symbian Nokia
			} elseif ( strstr($us_agt, 'Symbian/') || strstr($us_agt, 'Symbian OS')) { $OpSys['SymbianOS'] = array(@++$nb_SymbianOS, $icon_SymbianOS, '&nbsp;Symbian OS Nokia'); //For OS for Nokia - Symbian Nokia
			// SAMSUNG-GT-xxxx OS
			} elseif ( strstr($us_agt, 'SAMSUNG-GT')) { $OpSys['SAMSUNGGTOS'] = array(@++$nb_SymbianOS, $icon_pdaPhone, '&nbsp;SAMSUNG-GT-xxxx OS'); 
			// Nokia OS
			} elseif ( strstr($us_agt, 'Series40; Nokia') || strstr($us_agt, 'Nokia302/')) { $OpSys['NokiaOS'] = array(@++$nb_NokiaOS, $icon_pdaPhone, '&nbsp;Nokia OS'); 

			// Androïd
			//Mozilla/5.0 (Linux; U; Android 4.0.3; fr-fr; Transformer TF101 Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30"
			//"Mozilla/5.0 (Linux; U; Android 4.0.4; fr-fr; GT-N7000 Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30"
			//Mozilla/5.0 (Android; Linux armv7l; rv:5.0) Gecko/20110615 Firefox/5.0 Fennec/5.0
			} elseif ( strstr($us_agt, 'Android')) { $OpSys['AndroidOS'] = array(@++$nb_AndroidOS, $icon_AndroidOS, '&nbsp;Androïd OS mobile de Google'); // OS for Android
			//BlackBerry
			} elseif (strstr($us_agt, 'BlackBerry;')) { $OpSys['BlackBerry'] = array(@++$nb_BlackBerryOS, $icon_BlackBerryOS, '&nbsp;BlackBerry OS mobile');
			//WebOS (Palm)
			} elseif (strstr($us_agt, 'webOS') && strstr($us_agt, 'Safari')) { $OpSys['WebOSMobile'] = array(@++$nb_WebOSMobile, $icon_Linux, '&nbsp;iPhone OS');  //OS fonction avec noyau Linux (Palm)
			//J2ME/MIDP Device
			} elseif (strstr($us_agt, 'J2ME/MIDP')) { $OpSys['J2ME_MIDP'] = array(@++$nb_J2ME_MIDP, $icon_J2ME_MIDP, '&nbsp;J2ME/MIDP Device');  

			// Unkown OS Google Wireless Transcoder
			} elseif (strstr($us_agt, 'Google Wireless Transcoder'))  {  $OpSys['GoogleWireless'] = array(@++$nb_GoogleWirelessOS, $icon_Chrome, '&nbsp;Unkown OS Google Wireless'); 

			//------------------------------------------

			//25 juillet 2012 : OS X 10.8, dite « Mountain Lion » (Lion des montagnes)
			} elseif (preg_match('/Mac OS X 10_8|Mac OS X 10\.8/', $us_agt)) { $OpSys['MACOSXMountainLion'] = array(@++$nb_MACOSXMountainLion, $icon_Mac, '&nbsp;Mac OS X Mountain Lion (2012/07)');
			// 20 juillet 2011 : Mac OS X 10.7, dite « Lion » (Lion) 
			} elseif (preg_match('/Mac OS X 10_7|Mac OS X 10\.7/', $us_agt)) { $OpSys['MACOSXLion'] = array(@++$nb_MACOSXLion, $icon_Mac, '&nbsp;Mac OS X Lion (2011/07)');
			// 28 août 2009 : Mac OS X 10.6, dite « Snow Leopard » (Léopard des neiges)
			} elseif (preg_match('/Mac OS X 10_6|Mac OS X 10\.6/', $us_agt)) { $OpSys['MACOSXSnowLeopard'] = array(@++$nb_MACOSXSnowLeopard, $icon_Mac, '&nbsp;Mac OS X Snow Leopard (2009/08)');
			// 26 octobre 2007 : Mac OS X 10.5, dite « Leopard » (Léopard)
			} elseif (preg_match('/Mac OS X 10_5|Mac OS X 10\.5/', $us_agt)) { $OpSys['MACOSXLeopard'] = array(@++$nb_MACOSXLeopard, $icon_Mac, '&nbsp;Mac OS X Leopard (2007/10)');
			// 29 avril 2005 : Mac OS X 10.4, dite « Tiger » (Tigre)
			} elseif (preg_match('/Mac OS X 10_4|Mac OS X 10\.4/', $us_agt)) { $OpSys['MACOSXTiger'] = array(@++$nb_MACOSXTiger, $icon_Mac, '&nbsp;Mac OS X Tiger (2005/04)');
			// 24 octobre 2003 : Mac OS X 10.3, dite « Panther » (Panthère)
			} elseif (preg_match('/Mac OS X 10_3|Mac OS X 10\.3/', $us_agt)) { $OpSys['MACOSXPanther'] = array(@++$nb_MACOSXPanther, $icon_Mac, '&nbsp;Mac OS X Panther (2003/10)');
			// 24 août 2002 : Mac OS X 10.2, dite « Jaguar » (Jaguar)
			} elseif (preg_match('/Mac OS X 10_2|Mac OS X 10\.2/', $us_agt)) { $OpSys['MACOSXJaguar'] = array(@++$nb_MACOSXJaguar, $icon_Mac, '&nbsp;Mac OS X Jaguar (2002/08)');
			// 24 septembre 2001 : Mac OS X 10.1, dite « Puma » (Puma)
			} elseif (preg_match('/Mac OS X 10_1|Mac OS X 10\.1/', $us_agt)) { $OpSys['MACOSXPuma'] = array(@++$nb_MACOSXPuma, $icon_Mac, '&nbsp;Mac OS X Puma (2001/09)');			
			// 24 mars 2001 : Mac OS X 10.0, dite « Cheetah » (Guépard)
			} elseif (preg_match('/Mac OS X 10_0|Mac OS X 10\.0/', $us_agt)) { $OpSys['MACOSXCheetah'] = array(@++$nb_MACOSXCheetah, $icon_Mac, '&nbsp;Mac OS X Cheetah (2001/03)');			
			
			//Explanation: Yes MSIE does run on the MAC - here's the proof. String from Neil Thompson - thanks.
			//Mozilla/4.0 (compatible; MSIE 5.23; Mac_PowerPC)
			} elseif (strstr($us_agt, 'Mac_PowerPC')) { $OpSys['Mac_PowerPC'] = array(@++$nb_MACOSX, $icon_Mac, '&nbsp;Mac PowerPC, Mac OS X v5.2');

			} elseif (strstr($us_agt, 'Mac OS X')) { $OpSys['MACOSX'] = array(@++$nb_MACOSX, $icon_Mac, '&nbsp;Mac OS X');
			} elseif (strstr($us_agt, 'Mac OS')) { $OpSys['MACOS'] = array(@++$nb_MACOS, $icon_Mac, '&nbsp;Mac OS');

			} elseif (strstr($us_agt, 'FreeBSD')) { $OpSys['FreeBSD'] = array(@++$nb_FreeBSD, $icon_FreeBSD, '&nbsp;FreeBSD');
			} elseif (strstr($us_agt, 'SunOS')) { $OpSys['SunOS'] = array(@++$nb_SunOS, $icon_Solaris, '&nbsp;SunOS - Solaris');
			} elseif (strstr($us_agt, 'IRIX')) { $OpSys['IRIX'] = array(@++$nb_IRIX, $icon_IRIX, '&nbsp;IRIX');
			} elseif (strstr($us_agt, 'BeOS')) { $OpSys['BeOS'] = array(@++$nb_BeOS, $icon_BeOS, '&nbsp;BeOS');
			} elseif (strstr($us_agt, 'OS/2')) { $OpSys['OS2'] = array(@++$nb_OS2, $icon_OS2, '&nbsp;OS2');
			} elseif (strstr($us_agt, 'AIX')) { $OpSys['AIX'] = array(@++$nb_AIX, $icon_AIX, '&nbsp;AIX');
			} elseif (strstr($us_agt, 'Linux')) { $OpSys['Linux'] = array(@++$nb_Linux, $icon_Linux, '&nbsp;Linux');
			} elseif (strstr($us_agt, 'Unix')) { $OpSys['Unix'] = array(@++$nb_Unix, $icon_Unix, '&nbsp;Unix'); 
			//PlayStation Vita (OS ?)
			} elseif (strstr($us_agt, 'PlayStation Vita')) { $OpSys['PlayStationVita'] = array(@++$nb_PlayStationOS, $icon_PlayStation, '&nbsp;PlayStation Vita');
			} elseif (strstr($us_agt, 'PLAYSTATION ')) { $OpSys['PLAYSTATION'] = array(@++$nb_PlayStation, $icon_PlayStation, '&nbsp;PlayStation');

			} elseif (strstr($us_agt, 'Nintendo WiiU')) { $OpSys['NintendoWiiU'] = array(@++$nb_NintendoWiiUOS, $icon_Nitendo, '&nbsp;Nintendo WiiU');
			} elseif (strstr($us_agt, 'Nintendo Wii')) { $OpSys['NintendoWii'] = array(@++$nb_NintendoWiiUOS, $icon_Nitendo, '&nbsp;Nintendo Wii');			

			// Unkown OS but Lynx browser
			//Lynx/2.8.8dev.2 libwww-FM/2.14 SSL-MM/1.4.1
			} elseif (strstr($us_agt, 'Lynx/') && strstr($us_agt, 'libwww-FM/') && strstr($us_agt, 'SSL-MM/')) { $OpSys['Lynx'] = array(@++$nb_LynxOS, $icon_Lynx, '&nbsp;Unkown OS but Lynx Browser');


			} else {
				// Operating System Others or unknown or Not recognized by AllMyStats
				if (trim($us_agt)<>''){
					$Other_browsers_os_bots[] = $us_agt;
					$Other_type[] = 'OS Not recognized';
				}
			}

	} // Fin de For

	$show_page_os_nav_robots = '';

	//###############################################################################
	//							Operating System
	//###############################################################################
if ($display_operating_system == true && isset($OpSys)) {
	$display_operating_system = false;
	
	$show_page_os_nav_robots .= "
	<table style=\"".$table_border_CSS."\">
	  <tr>
		<td>
		  <table style=\"".$table_frame_CSS."\">
			<tr>
			  
			  <td style=\"width:5%; white-space:nowrap;\">"; //width: %; in px ne fonctionne pas
			  	if (isset($StatsIn_in_prot_dir) && $StatsIn_in_prot_dir <> 'Y') { // if use stats_in and is in protected directory --> the images can be displayed
					$show_page_os_nav_robots .= "
					&nbsp;&nbsp;<img src=\"".$path_allmystats_abs."images/icons/operating_system/os_logo.gif\" height=\"32px\" alt=\"".MSG_OPERATING_SYSTEM_USED."\" title=\"".MSG_OPERATING_SYSTEM_USED."\">";
			 	}
			  
			  $show_page_os_nav_robots .= "
			  </td>
			  <td style=\"".$table_title_CSS."\">".MSG_OPERATING_SYSTEM_USED."</td>
				</tr>
				<tr>
				  <td colspan=\"3\">
					
					<table style=\"".$table_data_CSS."\">
					  <tr>
						<th style=\"".$td_data_CSS." width=33%; text-align: center;\">".MSG_OPERATING_SYSTEM."</th>
						<th style=\"".$td_data_CSS." width=33%; text-align: center;\">".MSG_VISITORS."</th>
						<th style=\"".$td_data_CSS." text-align: center;\">".MSG_PERCENTAGE."</th>
					  </tr>";
						//----------- Display Operating System----------------------------------
						$total_OS = 0;
						// Obtient une liste de colonnes
						foreach ($OpSys as $key => $row) {
							$Nb_OpSys[$key]  = $row[0];
							$icons_OpSys[$key] = $row[1];
							$name_OpSys[$key] = $row[2];
							$total_OS = $total_OS+$Nb_OpSys[$key];
						}
						// Trie les données par Nb_browsers décroissant, name croissant
						// Ajoute $data en tant que dernier paramètre, pour trier par la clé commune
						@array_multisort($Nb_OpSys, SORT_DESC, $name_OpSys, SORT_ASC, $OpSys);
						
						foreach ($OpSys as $cle=>$valeur) {
							if(is_array($valeur)) {// si l'un des éléments est lui même un tableau alors on applique la fonction à ce tableau
				
								$show_page_os_nav_robots .= "
								<tr>
									<td style=\"".$td_data_CSS." text-align: left; vertical-align: top; white-space: nowrap;\">
										".$OpSys[$cle][1].$OpSys[$cle][2]."
									</td>
									<td style=\"".$td_data_CSS." white-space: nowrap; text-align: center;\">".$OpSys[$cle][0]."
									</td>
									<td style=\"".$td_data_CSS." white-space: nowrap; text-align: center;\">".(bcdiv($OpSys[$cle][0], $total_OS ,4)*100)."%
									</td>
								</tr>";
							}
						}
						unset($OpSys);
						unset($Nb_OpSys);
						unset($icons_OpSys);
						unset($name_OpSys);
						//----------------------------------------------------------------------	

		$show_page_os_nav_robots .= '</table></td></tr></table></td></tr></table><br />';
}

	//############################################################################################
	//							BROWSER
	//############################################################################################
 if ($display_browsers == true && isset($browsers)) {

	if(!isset($StatsIn_in_prot_dir)) { $StatsIn_in_prot_dir = ''; } // Si n'est pas include de stats_in
		
	$display_browsers = false;
	$show_page_os_nav_robots .= "
	<table style=\"".$table_border_CSS."\">
	  <tr>
		<td>
		  <table style=\"".$table_frame_CSS."\">
			<tr>
			  <td style=\"width:5%; white-space:nowrap;\">"; //width: %; in px ne fonctionne pas
				if (isset($StatsIn_in_prot_dir) && $StatsIn_in_prot_dir <> 'Y') { // if use stats_in and is in protected directory --> the images can be displayed			  	
					$show_page_os_nav_robots .= "
			  		&nbsp;&nbsp;<img src=\"".$path_allmystats_abs."images/icons/browsers/browsers_logo.gif\" height=\"32px\" alt=\"".MSG_BROWSERS_USED."\" title=\"".MSG_BROWSERS_USED."\">";
				}
				
			  $show_page_os_nav_robots .= "
			  </td>
			  <td style=\"".$table_title_CSS."\">".MSG_BROWSERS_USED."</td>
			 </tr>
			 <tr>
				<td colspan=\"2\">
					<table style=\"".$table_data_CSS."\">
					  <tr>
						<th style=\"".$td_data_CSS." width=33%; text-align: center;\">".MSG_BROWSERS."</th>
						<th style=\"".$td_data_CSS." width=33%; text-align: center;\">".MSG_VISITORS."</th>
						<th style=\"".$td_data_CSS." text-align: center;\">".MSG_PERCENTAGE."</th></tr>";
						
						//----------- Display Browsers -----------------------------------------
						//@array_multisort($browsers,SORT_DESC);
						$total_browers = 0;						
						// Obtient une liste de colonnes
						foreach ($browsers as $key => $row) {
							$Nb_browsers[$key]  = $row[0];
							$icons_browsers[$key] = $row[1];
							$name_browsers[$key] = $row[2];
							$total_browers = $total_browers + $Nb_browsers[$key];
						}
						// Tri les données par Nb_browsers décroissant, name croissant
						// Ajoute $data en tant que dernier paramètre, pour trier par la clé commune
						@array_multisort($Nb_browsers, SORT_DESC, $name_browsers, SORT_ASC, $browsers);
						
						foreach ($browsers as $cle=>$valeur) {
							if(is_array($valeur)) {// si l'un des éléments est lui même un tableau alors on applique la fonction à ce tableau
				
								$show_page_os_nav_robots .= "
								<tr>
								<td style=\"".$td_data_CSS." text-align: left; vertical-align: top; white-space: nowrap;\">
									".$browsers[$cle][1].$browsers[$cle][2]."</td>
								<td style=\"".$td_data_CSS." white-space: nowrap; text-align: center;\">
									".$browsers[$cle][0]."
								</td>
								<td style=\"".$td_data_CSS." white-space: nowrap; text-align: center;\">
									".(bcdiv($browsers[$cle][0], $total_browers ,4)*100)."%
								</td></tr>";
							}
						}
						unset($browsers);
						unset($Nb_browsers);
						unset($icons_browsers);
						unset($name_browsers);
						//----------------------------------------------------------------------	
				
						$show_page_os_nav_robots .= '</table></td></tr></table></td></tr></table><br />';
 }

				// -------------------- Test Add new OS, Browser or Bot -----------------------
				//$display_Other = true; //display other & unknown OS & Browser
				//-----------------------------------------------------------------------------
				if(isset($display_Other)) {
					unset($Tab_user_agent);
					//Lecture et Affichage de la liste des bad user_agent
					$result_agent = mysql_query("select user_agent from ".TABLE_BAD_USER_AGENT.""); 
					while($row = mysql_fetch_array($result_agent)){
						$Tab_user_agent[] = $row['user_agent'];
					}
			
					$Other_browsers_os_bots = array_unique($Other_browsers_os_bots);
					usort($Other_browsers_os_bots,"CompareValeurs");
																																
					for($i = 0; $i <= count($Other_browsers_os_bots); $i++){
						if (!in_array($Other_browsers_os_bots[$i], $Tab_user_agent) && !is_crawler($Other_browsers_os_bots[$i]) && $Other_browsers_os_bots[$i] != ';' ) {
							if (trim($Other_browsers_os_bots[$i])) { 
								//$Unknown[] = $Other_browsers_os_bots[$i]; 
								$browser = 'OtherBrowser'; $total=$total + 1; $$browser = $$browser + 1;
								if($display_Other) { echo 'Other OS, Browser or Bot : '.$Other_browsers_os_bots[$i].'<br>'; }
							}
						}
					}
				}
//--------------------------------------------------------------------------------------------
		if(isset($time_test) && $time_test == true) {
			$end = (float) array_sum(explode(' ',microtime()));  
			echo '<pre>										BROWSER, OS, BAD AGENT Traitement : '.sprintf("%.4f", $end-$start) . ' sec</pre>';
		}

 ?>	
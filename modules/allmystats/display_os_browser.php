<?php
/*
  -------------------------------------------------------------------------
 AllMyStats V1.75 - Statistiques site web - Web traffic analysis
 -------------------------------------------------------------------------
 Copyright (C) 2008-2010 - Herve Seywert
 copyright-GNU-xx.txt
 -------------------------------------------------------------------------
 Web:    http://allmystats.wertronic.com - http://www.wertronic.com
 -------------------------------------------------------------------------

Note:
Pour voir les user agent no répertoriés --> Admin-->bad user agent--> Add user agent
http://fr.wikipedia.org/wiki/User-Agent
			//http://www.webdevelopersnotes.com/design/list_of_browsers_for_linux_and_unix_systems.php3
*/

	// ---------------- Ne doit pas être appelé directement -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/display_os_browser.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

		if($time_test == true) {
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
		$icon_Lynx = '';
		//---------------------------------------------------------------------------------------

		$result = mysql_query("select agent from ".TABLE_UNIQUE_VISITOR." where date like '%".$when_date."'");
		$nbr_result = mysql_num_rows($result);

		for($i = 0; $i < $nbr_result; $i++) {
			$us_agt = mysql_result($result, $i, 'agent');

			if (strstr($us_agt, 'AOL')) { $browsers['AOL'] =  array(++$nb_AOL, $icon_AOL, '&nbsp;AOL');    //http://www.useragentstring.com/pages/AOL/
			} elseif (preg_match('/^Lynx/', $us_agt)) { $browsers['Lynx'] = array(++$nb_Lynx, $icon_Lynx, '&nbsp;Lynx'); //http://www.useragentstring.com/pages/Lynx/						
			
			} elseif (preg_match('/Opera 5\.|Opera\/5\./', $us_agt)) { $browsers['Opera5'] = array(++$nb_Opera5, $icon_Opera, '&nbsp;Opera v5.xx'); //http://www.useragentstring.com/pages/Opera/
			} elseif (preg_match('/Opera 6\.|Opera\/6\./', $us_agt)) { $browsers['Opera6'] = array(++$nb_Opera6, $icon_Opera, '&nbsp;Opera v6.xx');
			} elseif (preg_match('/Opera 7\.|Opera\/7\./', $us_agt)) { $browsers['Opera7'] = array(++$nb_Opera7, $icon_Opera, '&nbsp;Opera v7.xx');
			} elseif (preg_match('/Opera 8\.|Opera\/8\./', $us_agt)) { $browsers['Opera8'] = array(++$nb_Opera8, $icon_Opera, '&nbsp;Opera v8.xx');
			} elseif (preg_match('/Opera 9\.|Opera\/9\./', $us_agt)) { $browsers['Opera9'] = array(++$nb_Opera9, $icon_Opera, '&nbsp;Opera v9.xx');
			} elseif (preg_match('/Opera 9\.8|Version\/10\./', $us_agt)) { $browsers['Opera10'] = array(++$nb_Opera10, $icon_Opera, '&nbsp;Opera v10.xx');
			} elseif (strstr($us_agt, 'Opera'))  { $browsers['Opera'] = array(++$nb_Opera, $icon_Opera, '&nbsp;Opera'); 

			} elseif (strstr($us_agt, 'Konqueror')) { $browsers['Konqueror'] = array(++$nb_Konq, $icon_Konqueror, '&nbsp;Konqueror');  //http://www.useragentstring.com/pages/Konqueror/

			//IE http://www.useragentstring.com/pages/Internet%20Explorer/
			} elseif (strstr($us_agt, 'Mozilla/4.0') && strstr($us_agt, 'WebTV')) { $browsers['MSIE4WebTV'] = array(++$nb_MSIE4WebTV, $icon_MSIE, '&nbsp;Internet Exlorer 4 WebTV');
			} elseif (strstr($us_agt, 'MSIE 5.') &&  !strstr($us_agt, 'AOL')) { $browsers['MSIE5'] = array(++$nb_MSIE5, $icon_MSIE, '&nbsp;Internet Explorer 5.x');
			} elseif (strstr($us_agt, 'MSIE 6.0') && !strstr($us_agt, 'AOL')) { $browsers['MSIE6'] = array(++$nb_MSIE6, $icon_MSIE, '&nbsp;Internet Explorer 6.0');
			} elseif (strstr($us_agt, 'MSIE 7.0') && !strstr($us_agt, 'AOL')) { $browsers['MSIE7'] = array(++$nb_MSIE7, $icon_MSIE, '&nbsp;Internet Explorer 7.0');
			} elseif (strstr($us_agt, 'MSIE 8.0') && !strstr($us_agt, 'AOL')) { $browsers['MSIE8'] = array(++$nb_MSIE8, $icon_MSIE, '&nbsp;Internet Explorer 8.0');
			} elseif (strstr($us_agt, 'MSIE 9.0') && !strstr($us_agt, 'AOL')) { $browsers['MSIE9'] = array(++$nb_MSIE9, $icon_MSIE, '&nbsp;Internet Explorer 9.0');
			//IE Browser MOBILE (Microsoft PocketPC device and a Microsoft smartphone)
			} elseif (strstr($us_agt, 'MSIE 3.02'))  { $browsers['MSIE302'] = array(++$nb_MSIE302, $icon_MSIE, '&nbsp;Microsoft Mobile Explorer'); //Mozilla/2.0 (compatible; MSIE 3.02; Windows CE; 240x320)	
						
			} else {
				
				//Google Chrome
				if (strstr($us_agt, 'Chrome') && strstr($us_agt, 'Safari')) { 
					if(strstr($us_agt, 'Chrome/0.')) { $browsers['GChromev0'] = array(++$nb_GChromev0, $icon_Chrome, '&nbsp;Google Chrome v0.x');
					} elseif (strstr($us_agt, 'Chrome/1.')) { $browsers['GChromev1'] = array(++$nb_GChromev1, $icon_Chrome, '&nbsp;Google Chrome v1.x');
					} elseif (strstr($us_agt, 'Chrome/2.')) { $browsers['GChromev2'] = array(++$nb_GChromev2, $icon_Chrome, '&nbsp;Google Chrome v2.x');
					} elseif (strstr($us_agt, 'Chrome/3.')) { $browsers['GChromev3'] = array(++$nb_GChromev3, $icon_Chrome, '&nbsp;Google Chrome v3.x');
					} elseif (strstr($us_agt, 'Chrome/4.')) { $browsers['GChromev4'] = array(++$nb_GChromev4, $icon_Chrome, '&nbsp;Google Chrome v4.x');
					} elseif (strstr($us_agt, 'Chrome/5.')) { $browsers['GChromev5'] = array(++$nb_GChromev5, $icon_Chrome, '&nbsp;Google Chrome v5.x');
					} elseif (strstr($us_agt, 'Chrome/6.')) { $browsers['GChromev6'] = array(++$nb_GChromev6, $icon_Chrome, '&nbsp;Google Chrome v6.x');
					} elseif (strstr($us_agt, 'Chrome/7.')) { $browsers['GChromev7'] = array(++$nb_GChromev7, $icon_Chrome, '&nbsp;Google Chrome v7.x');
					} elseif (strstr($us_agt, 'Chrome/8.')) { $browsers['GChromev8'] = array(++$nb_GChromev8, $icon_Chrome, '&nbsp;Google Chrome v8.x');
					} elseif (strstr($us_agt, 'Chrome/9.')) { $browsers['GChromev9'] = array(++$nb_GChromev9, $icon_Chrome, '&nbsp;Google Chrome v9.x');
					} elseif (strstr($us_agt, 'Chrome/10.')) { $browsers['GChromev10'] = array(++$nb_GChromev10, $icon_Chrome, '&nbsp;Google Chrome v10.x');
					} elseif (strstr($us_agt, 'Chrome/11.')) { $browsers['GChromev11'] = array(++$nb_GChromev11, $icon_Chrome, '&nbsp;Google Chrome v11.x');
					} elseif (strstr($us_agt, 'Chrome/12.')) { $browsers['GChromev12'] = array(++$nb_GChromev12, $icon_Chrome, '&nbsp;Google Chrome v12.x');
					} elseif (strstr($us_agt, 'Chrome/13.')) { $browsers['GChromev13'] = array(++$nb_GChromev13, $icon_Chrome, '&nbsp;Google Chrome v13.x');
					} elseif (strstr($us_agt, 'Chrome/14.')) { $browsers['GChromev14'] = array(++$nb_GChromev14, $icon_Chrome, '&nbsp;Google Chrome v14.x');
					} elseif (strstr($us_agt, 'Chrome/15.')) { $browsers['GChromev11'] = array(++$nb_GChromev11, $icon_Chrome, '&nbsp;Google Chrome v11.x');
					} elseif (strstr($us_agt, 'Chrome/16.')) { $browsers['GChromev12'] = array(++$nb_GChromev12, $icon_Chrome, '&nbsp;Google Chrome v12.x');
					} elseif (strstr($us_agt, 'Chrome/17.')) { $browsers['GChromev13'] = array(++$nb_GChromev13, $icon_Chrome, '&nbsp;Google Chrome v13.x');
					} elseif (strstr($us_agt, 'Chrome/18.')) { $browsers['GChromev14'] = array(++$nb_GChromev14, $icon_Chrome, '&nbsp;Google Chrome v14.x');
					} elseif (strstr($us_agt, 'Chrome/19.')) { $browsers['GChromev14'] = array(++$nb_GChromev14, $icon_Chrome, '&nbsp;Google Chrome v14.x');
					} else { 
						$browsers['GChromeVunknown'] = array(++$nb_GChromeVunknown, $icon_Chrome, '&nbsp;Google Chrome unknown Ver');
					}
				} elseif (strstr($us_agt, 'Netscape') ) { //http://www.useragentstring.com/pages/Netscape/
					if (strstr($us_agt, 'Netscape6/6.1')) { $browsers['Netscape61'] = array(++$nb_Netscape61, $icon_Netscape, '&nbsp;Netscape 6.1');
					} elseif (strstr($us_agt, 'Netscape6/6.2')) { $browsers['Netscape62'] = array(++$nb_Netscape62, $icon_Netscape, '&nbsp;Netscape 6.2');
					} elseif (strstr($us_agt, 'Netscape6/7.')) { $browsers['Netscape7'] = array(++$nb_Netscape7, $icon_Netscape, '&nbsp;Netscape 7.x');
					} elseif (strstr($us_agt, 'Netscape6/8.')) { $browsers['Netscape8'] = array(++$nb_Netscape8, $icon_Netscape, '&nbsp;Netscape 8.x');
					} elseif (strstr($us_agt, 'Navigator/9.')) { $browsers['Netscape9'] = array(++$nb_Netscape9, $icon_Netscape, '&nbsp;Netscape 9.x');							
					} elseif (strstr($us_agt, 'Navigator/9.0b2.')) { $browsers['Netscape90b2'] = array(++$nb_Netscape90b2, $icon_Netscape, '&nbsp;Netscape 9.0b2');
					} elseif (strstr($us_agt, 'Navigator/9.0b3.')) { $browsers['Netscape90b3'] = array(++$nb_Netscape90b3, $icon_Netscape, '&nbsp;Netscape 9.0b3');
					} elseif (strstr($us_agt, 'Navigator/9.0RC1.')) { $browsers['Netscape90RC1'] = array(++$nb_Netscape90RC1, $icon_Netscape, '&nbsp;Netscape 9.0RC1');
					} else { 
						$browsers['NetscapeVunknown'] = array(++$nb_NetscapeVunknown, $icon_Netscape, '&nbsp;Netscape unknown ver');
					}
				//Nescape 0 to 4 (old)
				} elseif (preg_match('/^Mozilla\/[0-4]\.[0-8][0-9][ ]\[(en|en-US|en-gb|fr|de|it|pl|nl|es|ja|fi)\]|^Mozilla\/([0-4]\.[0-8][0-9][ ])\((Macintosh; U; PPC|Macintosh; I; PPC)\)/', $us_agt)) {
						if ( strstr($us_agt, 'Mozilla/2.') && strstr($us_agt, 'fr') ) { 
							$browsers['Netscape2'] = array(++$nb_Netscape2, $icon_Netscape, '&nbsp;Netscape 2.x');
						} elseif (strstr($us_agt, 'Mozilla/3.')) { 
							$browsers['Netscape3'] = array(++$nb_Netscape3, $icon_Netscape, '&nbsp;Netscape 3.x');
						} elseif (strstr($us_agt, 'Mozilla/4.')) { 
							$browsers['Netscape4'] = array(++$nb_Netscape4, $icon_Netscape, '&nbsp;Netscape 4.x');
						} else { 
							$browsers['NetscapeVunknown'] = array(++$nb_NetscapeVunknown, $icon_Netscape, '&nbsp;Netscape unknown ver');
						}
				//Firefox http://www.useragentstring.com/pages/Firefox/
				} elseif (strstr($us_agt, 'Firefox')) {
					if(strstr($us_agt, 'Firefox/0.')) { $browsers['Firefox0'] = array(++$nb_Firefox0, $icon_FireFox, '&nbsp;Mozilla Firefox v0.x');
					} elseif (strstr($us_agt, 'Firefox/1.')) { $browsers['Firefox1'] = array(++$nb_Firefox1, $icon_FireFox, '&nbsp;Mozilla Firefox 1.x');
					} elseif (strstr($us_agt, 'Firefox/2.')) { $browsers['Firefox2'] = array(++$nb_Firefox2, $icon_FireFox, '&nbsp;Mozilla Firefox 2.x');
					} elseif (strstr($us_agt, 'Firefox/3.')) { $browsers['Firefox3'] = array(++$nb_Firefox3, $icon_FireFox, '&nbsp;Mozilla Firefox 3.x');
					} elseif (strstr($us_agt, 'Firefox/4.')) { $browsers['Firefox4'] = array(++$nb_Firefox4, $icon_FireFox, '&nbsp;Mozilla Firefox 4.x');
					} elseif (strstr($us_agt, 'Firefox/5.')) { $browsers['Firefox5'] = array(++$nb_Firefox5, $icon_FireFox, '&nbsp;Mozilla Firefox 5.x');
					} elseif (strstr($us_agt, 'Firefox/6.')) { $browsers['Firefox6'] = array(++$nb_Firefox6, $icon_FireFox, '&nbsp;Mozilla Firefox 6.x');						
					} elseif (strstr($us_agt, 'Firefox/7.')) { $browsers['Firefox7'] = array(++$nb_Firefox4, $icon_FireFox, '&nbsp;Mozilla Firefox 7.x');
					} elseif (strstr($us_agt, 'Firefox/8.')) { $browsers['Firefox8'] = array(++$nb_Firefox5, $icon_FireFox, '&nbsp;Mozilla Firefox 8.x');
					} elseif (strstr($us_agt, 'Firefox/9.')) { $browsers['Firefox9'] = array(++$nb_Firefox6, $icon_FireFox, '&nbsp;Mozilla Firefox 9.x');						
					} else {
						$browsers['FirefoxVunknown'] = array(++$nb_FirefoxVunknown, $icon_FireFox, '&nbsp;Firefox unknown ver');
					} 
				//Safari http://www.useragentstring.com/pages/Safari/
				} elseif (preg_match('/Safari\/485|Safari\/125|Safari\/312|Safari\/100/', $us_agt)) { $browsers['Safari1'] = array(++$nb_Safari1, $icon_safari, '&nbsp;Safari 1.x');
				} elseif (preg_match("/Safari\/412|Safari\/416|Safari\/417|Safari\/419/", $us_agt)) { $browsers['Safari2'] = array(++$nb_Safari2, $icon_safari, '&nbsp;Safari 2.x');
				} elseif (strstr($us_agt, 'Safari/') && strstr($us_agt, 'Version/3.')) { $browsers['Safari3'] = array(++$nb_Safari3, $icon_safari, '&nbsp;Safari 3.x');
				} elseif (strstr($us_agt, 'Safari/') && strstr($us_agt, 'Version/4.')) { $browsers['Safari4'] = array(++$nb_Safari4, $icon_safari, '&nbsp;Safari 4.x');
				} elseif (strstr($us_agt, 'Safari/') && strstr($us_agt, 'Version/5.')) { $browsers['Safari5'] = array(++$nb_Safari5, $icon_safari, '&nbsp;Safari 5.x');
				} elseif (strstr($us_agt, 'Safari/') && strstr($us_agt, 'Version/6.')) { $browsers['Safari6'] = array(++$nb_Safari6, $icon_safari, '&nbsp;Safari 6.x');
				} elseif (strstr($us_agt, 'Safari/') && strstr($us_agt, 'Version/7.')) { $browsers['Safari7'] = array(++$nb_Safari6, $icon_safari, '&nbsp;Safari 7.x');

				//Powermarks see http://www.kaylon.com/
				} elseif (strstr($us_agt, 'Powermarks/')) { $browsers['Powermarks'] = array(++$nb_Powermarks, '', '&nbsp;Powermarks');

				// ----------- Mobile browser -------------------------------
				} elseif (strstr($us_agt, 'iPhone') && strstr($us_agt, 'Safari')) { $browsers['iPhoneSafari'] = array(++$nb_iPhoneSafari, $icon_pdaPhone, '&nbsp;iPhone Safari');
				} elseif (strstr($us_agt, 'iPad') && strstr($us_agt, 'Safari')) { $browsers['iPadSafari'] = array(++$nb_iPadSafari, $icon_pdaPhone, '&nbsp;iPad Safari');
				} elseif (strstr($us_agt, 'iPod') && strstr($us_agt, 'Safari')) { $browsers['iPodSafari'] = array(++$nb_iPodSafari, $icon_pdaPhone, '&nbsp;iPod Safari');

				} elseif (strstr($us_agt, 'Nokia') && strstr($us_agt, 'Opera')) { $browsers['NokiaOpera'] = array(++$nb_NokiaOpera, $icon_pdaPhone, '&nbsp;Nokia Opera');
				} elseif (strstr($us_agt, 'Nokia') && strstr($us_agt, 'BrowserNG')) { $browsers['NokiaBrowser'] = array(++$nb_NokiaBrowser, $icon_pdaPhone, '&nbsp;Nokia Browser (PDA/Phone browser)');
				} elseif (strstr($us_agt, 'Nokia')) { $browsers['Nokia'] = array(++$nb_Nokia, $icon_pdaPhone, '&nbsp;Nokia');

				} elseif (preg_match('/^BlackBerry /', $us_agt)) { $browsers['BlackBerry'] = array(++$nb_BlackBerry, $icon_pdaPhone, '&nbsp;BlackBerry');
				} elseif (preg_match('/^LG /', $us_agt) && preg_match('/lg-|LG\/|LGE/', $us_agt)) {  $browsers['LGElectronics'] = array(++$nb_LGElectronics, $icon_pdaPhone, '&nbsp;LG Electronics');
				} elseif (preg_match('/^Motorola /', $us_agt)) { $browsers['Motorola'] = array(++$nb_Motorola, $icon_pdaPhone, '&nbsp;Motorola');
				} elseif (strstr($us_agt, 'SAMSUNG-SGH') && preg_match('/NetFront\/|Browser/', $us_agt)) { $browsers['SAMSUNGNetfront'] = array(++$nb_SSAMSUNGNetfront, $icon_pdaPhone, '&nbsp;Samsung NetFront');
				} elseif (strstr($us_agt, 'SonyEricsson') && strstr($us_agt, 'NetFront/')) { $browsers['SonyEricssonNetFront'] = array(++$nb_SonyEricssonNetFront, $icon_pdaPhone, '&nbsp;Sony/Ericsson NetFront');
				} elseif ( (strstr($us_agt, 'webOS') && strstr($us_agt, 'Safari')) || (strstr($us_agt, 'Windows 98') && strstr($us_agt, 'PalmSource')) ) { $browsers['HPPalm'] = array(++$nb_HPPalm, $icon_pdaPhone, '&nbsp;HP Palm');

				//----------------------------------------------------------
				//Others or unknown
				} else { 
					if (trim($us_agt)<>''){
						$Other_browsers_os_bots[] = $us_agt; 
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
			$icon_Linux = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/linux.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"linux\" title=\"linux\">";
			$icon_FreeBSD = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/freebsd.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"FreeBsd\" title=\"FreeBsd\">";
			$icon_Unix = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/unix.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Unix\" title=\"Unix\">";
			$icon_Solaris = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/solaris.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"SunOS - Solaris\" title=\"SunOS - Solaris\">";
			$icon_BeOS = '';
			$icon_OS_2 = '';
			$icon_Aix = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/aix.png\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"AIX\" title=\"AIX\">";
			$icon_SymbianOS = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/symbian_os.gif\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"Symbian OS\" title=\"Symbian OS\">";
			$icon_HPPalm = "<img src=\"".$path_allmystats_abs."images/icons/operating_system/hp_palm.gif\" style=\"vertical-align:bottom\" height=\"14\" width=\"14\" alt=\"WebOS HP Palm\" title=\"WebOS HP Palm\">";

			//----------------------------------------------------------------------------------
			
			if(strstr($us_agt, 'Windows NT 6.1')) { $OpSys['Windows7'] = array(++$nb_Windows7, $icon_Win_2, '&nbsp;Windows 7'); 
			} elseif (strstr($us_agt, 'Windows NT 6.0')) { $OpSys['WindowsVista'] = array(++$nb_WindowsVista, $icon_vista, '&nbsp;Windows Vista'); 
			} elseif (strstr($us_agt, 'Windows NT 5.2')){ $OpSys['WindowsServer2003'] = array(++$nb_WindowsServer2003, $icon_Win_2, '&nbsp;Windows Server 2003'); 
			} elseif (strstr($us_agt, 'Windows NT 5.1')) { $OpSys['WindowsXP'] = array(++$nb_WindowsXP, $icon_Win_2, '&nbsp;Windows XP');
			} elseif (strstr($us_agt, 'Windows NT 5.0')) { $OpSys['Windows2000'] = array(++$nb_Windows2000, $icon_Win, '&nbsp;Windows 2000'); 
			} elseif (strstr($us_agt, 'Windows NT 4'))  { $OpSys['WindowsNT'] = array(++$nb_WindowsNT, $icon_Win, '&nbsp;Windows NT');
			//DigExt extension in certain versions of MSIE Grabs content to make it available offline
			} elseif (strstr($us_agt, 'Windows NT') && strstr($us_agt, 'DigExt'))  {  $OpSys['WindowsNT'] =  array(++$nb_WindowsNT, $icon_Win, '&nbsp;Windows NT');
			
			} elseif (preg_match('/Windows 98|Win98|Windows ME|Win 9x 4\.90/', $us_agt)) { $OpSys['Windows98'] = array(++$nb_Windows98, $icon_Win, '&nbsp;Windows 98');
			
			} elseif (strstr($us_agt, 'Windows 95')) { $OpSys['Windows95'] = array(++$nb_Windows95, $icon_Win, '&nbsp;Windows 95');
			//device_os	Windows Mobile OS
			} elseif (strstr($us_agt, 'Windows CE')) { $OpSys['WindowsCE'] = array(++$nb_WindowsCE, $icon_Win_2, '&nbsp;Windows CE');

			} elseif (strstr($us_agt, 'Mac OS X 10_6')) { $OpSys['MACOSXSnowLeopard'] = array(++$nb_SnowLeopard, $icon_Mac, '&nbsp;Mac OS X Snow Leopard');
			} elseif (preg_match('/Mac OS X 10_5|Mac OS X 10\.5/', $us_agt)) { $OpSys['MACOSXLeopard'] = array(++$nb_MACOSLeopard, $icon_Mac, '&nbsp;Mac OS X Leopard');
			} elseif (strstr($us_agt, 'Mac OS X 10_4')) { $OpSys['MACOSXTiger'] = array(++$nb_MACOSXTiger, $icon_Mac, '&nbsp;Mac OS X Tiger');
			} elseif (strstr($us_agt, 'Mac OS X')) { $OpSys['MACOSX'] = array(++$nb_MACOSX, $icon_Mac, '&nbsp;Mac OS X');
			} elseif (strstr($us_agt, 'Mac OS')) { $OpSys['MACOS'] = array(++$nb_MACOS, $icon_Mac, '&nbsp;Mac OS');

			} elseif (strstr($us_agt, 'FreeBSD')) { $OpSys['FreeBSD'] = array(++$nb_FreeBSD, $icon_FreeBSD, '&nbsp;FreeBSD');
			} elseif (strstr($us_agt, 'SunOS')) { $OpSys['SunOS'] = array(++$nb_SunOS, $icon_Solaris, '&nbsp;SunOS - Solaris');
			} elseif (strstr($us_agt, 'IRIX')) { $OpSys['IRIX'] = array(++$nb_IRIX, $icon_IRIX, '&nbsp;IRIX');
			} elseif (strstr($us_agt, 'BeOS')) { $OpSys['BeOS'] = array(++$nb_BeOS, $icon_BeOS, '&nbsp;BeOS');
			} elseif (strstr($us_agt, 'OS/2')) { $OpSys['OS2'] = array(++$nb_OS2, $icon_OS2, '&nbsp;OS2');
			} elseif (strstr($us_agt, 'AIX')) { $OpSys['AIX'] = array(++$nb_AIX, $icon_AIX, '&nbsp;AIX');
			} elseif (strstr($us_agt, 'Linux')) { $OpSys['Linux'] = array(++$nb_Linux, $icon_Linux, '&nbsp;Linux');
			} elseif (strstr($us_agt, 'Unix')) { $OpSys['Unix'] = array(++$nb_Unix, $icon_Unix, '&nbsp;Unix'); 

			//------------ Mobiles ---------------------
			//Mac OS Device
			} elseif ( strstr($us_agt, 'Mac OS') && preg_match('/iPhone|iPad|iPod/', $us_agt) ) { $OpSys['MacOSMobile'] = array(++$nb_MacOSMobile, $icon_Mac, '&nbsp;MacOSMobile'); //For Apple iPhone, iPad, iPod touch
			//Iphone OS (Mac)
			} elseif ( strstr($us_agt, 'iPhone OS') ) { $OpSys['iPhoneOS'] = array(++$nb_iPhoneOS, $icon_Mac, '&nbsp;iPhone OS');   //For Apple iPhone, iPad, iPod touch
			//Symbian OS
			} elseif ( strstr($us_agt, 'SymbianOS') || strstr($us_agt, 'Symbian OS')) { $OpSys['SymbianOS'] = array(++$nb_SymbianOS, $icon_SymbianOS, '&nbsp;Symbian OS'); //For OS for Nokia
			//WebOS (Palm)
			} elseif (strstr($us_agt, 'webOS') && strstr($us_agt, 'Safari')) { $OpSys['WebOSMobile'] = array(++$nb_WebOSMobile, $icon_Linux, '&nbsp;iPhone OS');  //OS fonction avec noyau Linux (Palm)
			//------------------------------------------

			} else {
				if (trim($us_agt)<>''){
					$Other_browsers_os_bots[] = $us_agt; 
				}
			}


	} // Fin de For

	$show_page_os_nav_robots = '';


	############################## Bad user agent ###################################
				include(FILENAME_DISPLAY_BAD_AGENTS);
	############################## Bad user agent ###################################

	//###############################################################################
	//							Operating System
	//###############################################################################
if ($display_operating_system == true && $OpSys) {
	$display_operating_system = false;
	
	$show_page_os_nav_robots .= "
	<table style=\"".$table_border_CSS."\">
	  <tr>
		<td>
		  <table style=\"".$table_frame_CSS."\">
			<tr>
			  
			  <td style=\"width:5%; white-space:nowrap;\">"; //width: %; in px ne fonctionne pas
			  	if ($StatsIn_in_prot_dir <> 'Y') { // if use stats_in and is in protected directory --> the images can be displayed
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
									<td style=\"".$td_data_CSS."\" white-space: nowrap;\">
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
 if ($display_browsers == true && $browsers) {
	$display_browsers = false;
	$show_page_os_nav_robots .= "
	<table style=\"".$table_border_CSS."\">
	  <tr>
		<td>
		  <table style=\"".$table_frame_CSS."\">
			<tr>
			  <td style=\"width:5%; white-space:nowrap;\">"; //width: %; in px ne fonctionne pas
				if ($StatsIn_in_prot_dir <> 'Y') { // if use stats_in and is in protected directory --> the images can be displayed			  	
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
						// Trie les données par Nb_browsers décroissant, name croissant
						// Ajoute $data en tant que dernier paramètre, pour trier par la clé commune
						@array_multisort($Nb_browsers, SORT_DESC, $name_browsers, SORT_ASC, $browsers);
						
						foreach ($browsers as $cle=>$valeur) {
							if(is_array($valeur)) {// si l'un des éléments est lui même un tableau alors on applique la fonction à ce tableau
				
								$show_page_os_nav_robots .= "
								<tr>
								<td style=\"".$td_data_CSS."\" white-space: nowrap;\">
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

				//-----------------------------------------------------------------------------
				// -------------------- Test Add new OS, Browser or Bot -----------------------
				//$display_Other = true; //display other & unknown OS & Browser
				//-----------------------------------------------------------------------------
				if($display_Other) {
					//Mise en en forme ($AllBots) pour preg_match des bot connus (dans la table + bot en général (bot, spider , etc)
					$result_bots = mysql_query("select bot_name, org_name, crawler_url, crawler_info from ".TABLE_CRAWLER.""); 
					$AllBots = '/Bot|Slurp|Scooter|Spider|crawl|'; //del Agent because error on user agent
					while($row = mysql_fetch_array($result_bots)){
						$Form_chaine = str_replace('/','\/',$row['bot_name']);
						$Form_chaine = str_replace('+','\+',$Form_chaine);
						$Form_chaine = str_replace('(','\(',$Form_chaine);
						$Form_chaine = str_replace(')','\)',$Form_chaine);
						$AllBots .= $Form_chaine.'|';
					}
					$AllBots = substr($AllBots, 0, strlen($AllBots)-1); //delete last "|"
					$AllBots .= '/i';
					//-------------------------------------------------------------------------
					unset($Tab_user_agent);
					//Lecture et Affichage de la liste des bad user_agent
					$result_agent = mysql_query("select user_agent from ".TABLE_BAD_USER_AGENT.""); 
					while($row = mysql_fetch_array($result_agent)){
						$Tab_user_agent[] = $row['user_agent'];
					}
			
					$Other_browsers_os_bots = array_unique($Other_browsers_os_bots);
					usort($Other_browsers_os_bots,"CompareValeurs");
																																
					for($i = 0; $i <= count($Other_browsers_os_bots); $i++){
						if (!in_array($Other_browsers_os_bots[$i], $Tab_user_agent) && !preg_match($AllBots, $Other_browsers_os_bots[$i]) && $Other_browsers_os_bots[$i] != ';' ) {
							if (trim($Other_browsers_os_bots[$i])) { 
								//$Unknown[] = $Other_browsers_os_bots[$i]; 
								$browser = 'OtherBrowser'; $total=$total + 1; $$browser = $$browser + 1;
								if($display_Other) { echo 'Other OS, Browser or Bot : '.$Other_browsers_os_bots[$i].'<br>'; }
							}
						}
					}
				}
//--------------------------------------------------------------------------------------------
		if($time_test == true) {
			$end = (float) array_sum(explode(' ',microtime()));  
			echo '<pre>										BROWSER, OS, BAD AGENT Traitement : '.sprintf("%.4f", $end-$start) . ' sec</pre>';
		}

 ?>	
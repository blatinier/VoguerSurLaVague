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

 define the database table names used in the project
*/

 if(!isset($mysql_prefix)) {
 	$mysql_prefix = 'allmystats'; //for anterior (v1.60) compatibility
 }

  define('TABLE_UPDATES', $mysql_prefix.'_updates');

  define('TABLE_ARCHIVE', $mysql_prefix.'_archive');
  define('TABLE_BAD_USER_AGENT', $mysql_prefix.'_bad_user_agent');
  define('TABLE_CRAWLER', $mysql_prefix.'_crawler');
  
  define('TABLE_MONTHLY_KEYWORDS', $mysql_prefix.'_monthly_keywords');

  define('TABLE_DAYS_KEYWORDS', $mysql_prefix.'_days_keywords');
  define('TABLE_DAYS_PAGES', $mysql_prefix.'_days_pages');

  define('TABLE_UNIQUE_VISITOR', $mysql_prefix.'_unique_visitor');
  define('TABLE_PAGE_VISITOR', $mysql_prefix.'_page_visitor');

  define('TABLE_UNIQUE_BOT', $mysql_prefix.'_unique_bot');
  define('TABLE_PAGE_BOT', $mysql_prefix.'_page_bot');

  define('TABLE_UNIQUE_BAD_AGENT', $mysql_prefix.'_unique_bad_agent');
  define('TABLE_PAGE_BAD_AGENT', $mysql_prefix.'_page_bad_agent');

?>

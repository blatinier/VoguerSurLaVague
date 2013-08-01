<?
/*
 -------------------------------------------------------------------------
 AllMyStats V1.59 - Statistiques, audience de site web
 -------------------------------------------------------------------------
 Copyright (C) 2008 - Herve Seywert
 -------------------------------------------------------------------------
 Web:    http://allmystats.wertronic.com - http://www.wertronic.com
 -------------------------------------------------------------------------
 copyright-GNU-xx.txt
 -------------------------------------------------------------------------
//Add 23-09-2011
define('MSG_NOTE_ADD_BOT' TODO traduct
*/


// Language file : Russian
//Petr added New (search new)
define('MSG_TOP','Топ');
define('MSG_UNKNOWN_OR_DIRECT','Неизвестно или прямой');
define('MSG_DEVELOPED_BY', 'Создано');

define('MSG_HELP','Справка');
define('MSG_ACCUEIL','Главная страница');
define('MSG_ARCHIVE','Архив');
define('MSG_MONTHLY','Ежемесячно');
define('MSG_MONTH','Месяц:');
define('MSG_CURRENTLY_MONTHLY','В настоящее время месяца архив');
define('MSG_DATES','Даты');
define('MSG_TITLE_ORG_GEO','географическое происхождение');
define('MSG_COUNTRY','Страна');
define('MSG_GRAPH_HOURS','Часы');
define('MSG_GRAPH_DAY','дня');
define('MSG_YESTERDAY','Вчера');
define('MSG_MONTH_HISTO','Месяц истории');
define('MSG_MONTH_ARCHIVED','Архив');
define('MSG_MONTH_CACHED','Сохранено в кэше');
define('MSG_MONTH_COMPLETED_CACHE_FILE', 'Complete cache file'); //new
define('MSG_WARNING_MONTH_NOT_CACHED','Один или несколько месяцев не кэшируются<br/>Изменить месяцев, которые таковыми не являются.');
define('MSG_MONTH_NOT_CACHED','не кэшируются');
define('MSG_IN_PROGRESS','В Прогресс');
define('MSG_BROWSERS_USED','Браузеры использовать');
define('MSG_BROWSERS','Браузеры');
define('MSG_OPERATING_SYSTEM_USED','Операционные системы, используемой');
define('MSG_OPERATING_SYSTEM','Операционные системы');
define('MSG_PAGE','Страницы');
define('MSG_PAGES_PERCENTAGE','% Страницы / Общая');
define('MSG_VISITED_PAGES','посещение страницы');
define('MSG_SINCE_BEGIN_MONTH','С начала текущего месяца');
define('MSG_KEYWORDS','Ключевые слова');
define('MSG_REFERERS_AND_KEYWORDS','реферралам и ключевые слова');
define('MSG_REFERERS','реферралам');
define('MSG_BACK','Назад');
define('MSG_DIFFERENTS','Дифференц');
define('MSG_LOGOUT','Выход');
define('MSG_ADWORDS_CONTENT_NETWORK','<font color=#333333>(сеть контекстной рекламы Adwords)</font>');
define('MSG_LAST_VISITOR','Последний посетитель');
define('MSG_GRAPH_HOUR_VISITORS_PAGES','Посетители и посещение страниц / час');
define('MSG_GRAPH_DAY_VISITORS_PAGES','посещение страниц и Пришельцы / день<br>');
define('MSG_GRAPH_MONTH_VISITORS_PAGES','Просмотренные страницы и посетителей - Месяц:');
define('MSG_NUMBER_VISITED_PAGES','Количество посещенных страниц');
define('MSG_VISITED_PAGES_BY_VISITOR','Количество посещенных страниц / посетитель');
define('MSG_FIRST_VISITOR','Первый посетитель');
define('MSG_NUMBER_VISTORS','Количество посетителей');
define('MSG_REPORT_AUDIENCE','аудитории доклад');
define('MSG_REVERSE_DNS','Обратный ДНС');
define('MSG_TOP_VISITORS','Топ 20 посетителей');
define('MSG_VISITORS_AND_ROBOTS','Посетителям + боты');
define('MSG_VISITORS','Посетители');
define('MSG_PERCENTAGE_VISITORS_OS','% Посетители / операционная система');
define('MSG_PERCENTAGE_VISITORS_BOTS','% Посещения / Бот');
define('MSG_PERCENTAGE','%');
define('MSG_ORIGIN_UNKNOWN','Происхождение неизвестно');
define('MSG_TOTAL_DIFFERENT_KEYWORDS','Всего различные ключевые слова');
define('MSG_TOTAL_DIFFERENT_KEYWORDS_DAY','Всего различные ключевые слова на сегодняшний день');
define('MSG_TOTAL_DIFFERENT_PAGES','Всего различные страницы');
define('MSG_TOTAL_PAGES_VISITED','Всего страниц посетил');
define('MSG_TOTAL_VISITORS','Всего пользователей');
define('MSG_TOTAL_DIFFERENT_COUNTRIES','Всего различных стран');
define('MSG_DIRECTORIE_UPDATE_EXIST','каталог обновлений / это Кликнуть кнопку ниже, чтобы удалить!');
define('MSG_DIRECTORIE_INSTALL_EXIST','Каталог установки / это Кликнуть кнопку ниже, чтобы удалить!');
define('MSG_COMPLETE_LIST','Показать полный список');
define('MSG_SHORTLIST','Показывать короткий список');

//--------------- General ---------------
define('MSG_HOUR', 'Hour');

// --------------------------------------

//--------------------------------------------------------------
define('MSG_EXCLUDED_BOTS','Исключенные ботов');
define('MSG_VISITED_PAGES_BY_BOTS','посещенных страниц по ботов');
define('MSG_OTHER_BOTS','Другие роботы');
define('MSG_BOT_VISITS','Bot посещений');
define('MSG_BOT_PARENT_NAME','Название (подробнее)');
define('MSG_BOT_NAME','имя робота');
define('MSG_BOTS_NB_PAGES_SCANNED','Количество отсканированные страницы');
define('MSG_NB_DISTINCT_IP','различные IP');
define('MSG_BOTS_KNOW_IN_DATABASE','Боты определенных в базе данных:');
define('MSG_DETAILS_UNKNOWN_BOTS','Подробная информация о роботы, которые не определены в базе данных:');
define('MSG_BOTS_OS_BROWSER_UNKNOWN','Агент пользователя: ОС, браузеры, Роботы Непризнанные');
define('MSG_BAD_USER_AGENT_S_I','плохой агент пользователя СПАМ (S) и<br />агент пользователя неизвестна (I)'); //Not use 08-10-2010
define('MSG_USER_AGENT','Пользователь агент');
define('MSG_LEFT_VISITORS', 'Посетители ????');

//----- New - 10-10-2010 ----------
define('MSG_USER_AGENT_SPAM', 'Spam (S)');
define('MSG_USER_AGENT_UNKNOWN', 'Unknown (I)');
define('MSG_USER_AGENT_OTHER', 'Other (A)');
define('MSG_USER_AGENT_UNKNOWN_LIST', 'User Agent not recognized, can be a OS, browser, bot or spam');
define('MSG_USER_AGENT_NO_UNKNOWN_LIST', 'No unknown user agent found in MySQL visits');
//---------------------------------

define('MSG_COMMENTS','Комментарии');
define('MSG_SCOREBOARD','табло');
define('MSG_DESCRIPTION','Описание');
define('MSG_VALUE','Значение');
define('MSG_TOTAL','Общая');
define('MSG_STATISTICS_OF','Статистика:');
define('MSG_NB_VISITORS','Количество посетителей');
define('MSG_BOTS_GRAPH_HOUR','Поисковые системы: Диаграмма / час');
define('MSG_VISITED_PAGES_BY_BOT','Количество посещенных страниц / бот');
define('MSG_NB_VISITED_PAGES_BOTS','Количество посещенных страниц ботов');
define('MSG_NB_VISITS_BOTS','Количество визитов ботами');
//--------------------------------------------------------------

define('MSG_REFRESH','Обновить');
define('MSG_VISITEURS','Посетители');
define('MSG_VISITEURS_AND_BOTS','Посетители + Роботы');
define('MSG_NO_COUNT_VISITS','<font color=#ff0000>Не считайте ваши посещения с этого компьютера </font>.');
define('MSG_CLICK_HERE','Нажмите здесь');
define('MSG_ADMIN_MY_VISITS_MANAGEMENT','Мои визиты управления');
define('MSG_ADMIN_TOOLS','Admin');
define('MSG_ADMIN_TOOLS_MENU','Admin основных');
define('MSG_ADMIN_TOOLS_MY_VISITS','Мои визиты');
define('MSG_ADMIN_TOOLS_BOTS','боты');
define('MSG_ADMIN_TOOLS_NO_UNKNOWN_BOT','Нет неизвестных робота для добавления');
define('MSG_ADMIN_TOOLS_BOTS_LIST','Список ботов (сканеры)');
define('MSG_ADMIN_TOOLS_PASSW','пользователя и пароль');
define('MSG_ADMIN_TOOLS_CHGT_PASSW','Смена пользователя и пароль');
define('MSG_ADMIN_TOOLS_CHGT_USER_PASSW_SUCCESS','пользователя и пароль были успешно изменен');
define('MSG_ADMIN_TOOLS_CONFIRM_PASSW_OUT','Подтверждение пароль неверный');

define('MSG_ADMIN_DOWNLOAD_GEOIP_DAT', '<strong>Geolocation Update database:</strong> <a href="http://geolite.maxmind.com" target="_blank">(Maxmind)</a><br>
You can download the GeoIP.dat file updated the: 05 each month<br>
uncompress the file and replace it in your directory allmystats /lib/geoip/dat/<br>
<a href="http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz" target="_blank">Database download (commercial)</a><br>
GeoIP country is a more accurate version (commercial): <a href="http://www.maxmind.com/fr/country" target="_blank">GeoIP country more accurate version (commercial)</a>');
//-----------------------------------------------------------------
//bad_user_agent
define('MSG_BAD_USER_AGENT','Bad агент пользователя');
define('MSG_TOOLS_CONFIRM_DELETE','Вы уверены, что хотите удалить');
define('MSG_ADD','Добавить в');
define('Msg_type','типа');
define('MSG_ACTION','действий');
define('MSG_DELETE','Удалить');
define('MSG_EDIT','Изменить');
define('MSG_CANCEL','Отмена');
define('MSG_BOTS','Боты (сканеры)');
define('MSG_ADMIN_BOT_PARENT_NAME','Bot родителей Name');
define('MSG_TOOLS_BOT_URL','Бот URL');
define('MSG_TOOLS_DELETE_SUCCESS','Было успешно удалено.');
define('MSG_TOOLS_ADD_SUCCESS','был успешно добавлен в базу данных.');
define('MSG_TOOLS_MODIFIE_SUCCESS','успешно');

define('MSG_TOOLS_BOT_UPDATE_TABLE', '<strong>Udate table crawler<br>WARNING:</strong> the SQL table crawler will be fully replaced<br>');
define('MSG_TOOLS_BOT_IMPORT_SUCCESS', '&nbsp;&nbsp;&nbsp;<strong>able: crawler has been imported successfully</strong><br>');
define('MSG_TOOLS_BOT_IMPORT_HTTP', '1st solution: import the Crawler table maintained by AllMyStats<br>');
define('MSG_TOOLS_BOT_IMPORT_LOCAL', '2nd solution: download the file .zip to this address:  <a href="http://allmystats.wertronic.com/download/sql_update/allmystats_crawler.zip" target="_blank">allmystats_crawler.zip</a><br>
		unzip and put it in your directory allmystats/includes/sql/<br>');

define('MSG_TOOLS_BAD_AGENT_UPDATE_TABLE', '<strong>Udate table Bad User Agent<br>WARNING:</strong> the SQL table Bad User Agent will be fully replaced<br>');
define('MSG_TOOLS_BAD_AGENT_IMPORT_SUCCESS', '&nbsp;&nbsp;&nbsp;<strong>able: Bad User Agent has been imported successfully</strong><br>');
define('MSG_TOOLS_BAD_AGENT_IMPORT_HTTP', '1st solution: import the Bad User Agent table maintained by AllMyStats<br>');
define('MSG_TOOLS_BAD_AGENT_IMPORT_LOCAL', '2nd solution: download the file .zip to this address:  <a href="http://allmystats.wertronic.com/download/sql_update/allmystats_bad_user_agent.zip" target="_blank">allmystats_bad_user_agent.zip</a><br>
		unzip and put it in your directory allmystats/includes/sql/<br>');

//-----------------------------------------------------------------
//-----------------------------------------------------------------
define('MSG_CHECK','Check');
define('MSG_INSTALL_COOKIE','Install a Cookie');
define('MSG_DELETE_COOKIE','Delete Cookie');
define('MSG_COOKIE_INSTALLED','The cookie is installed');
define('MSG_COOKIE_DELETED', 'The cookie is deleted');
define('MSG_THIS_PC','IP of your current connection');
define('MSG_VISITS_FROMTHIS_RECORDED','The visits from this computer are recorded in the statistics.');

define('MSG_VISITS_FROMTHIS_RECORDED_DETAILS', "For visits from this computer are not counted in the statistics.
<br>
<b>A cookie must be installed.</b><br>.");

define('MSG_IF_BROWSER_ACCEPT_COOKIES','If your browser accepts cookies');

if(!isset($site)) { $site = ''; }
define('MSG_VISITS_FROMTHIS_NOT_RECORDED','Visits of the site: '.$site.' made from this computer are not counted in the statistics.');

define('MSG_COOKIE_AND_IP_INSTALLED','A cookie is installed and the IP address that this connection is defined in config_allmystats.php. <br />
If you want to record visits from that computer you must delete the cookie and delete the IP address of the file config_allmystats.php <br /><br />');

define('MSG_IF_YOU_WANT_RECORD_THIS','If you want to record visits from this computer:');

define('MSG_IP_ADRESS_IS_DEFINED_DETAILS','The IP address that this connection is defined in config_allmystats.php.
<br /><br /><strong>If your browser accepts cookies, it may be preferable to place a cookie (because your IP is perhaps dynamic): </strong>');

define('MSG_IP_WHOSE_NOT_RECORDED','<strong>IP whose visits are not recorded: </strong> (IP defined in config_allmystats.php)<br />');

define('MSG_NO_IP_DEFINITED','No IP address is defined.');
//-------------------------------------------------------------

//--------------- Install AllMyStats -------------------------------------------------------
define('MSG_INSTALL_TITLE','AllMyStats setup<br />Statistics for Web sites');
define('MSG_INSTALL_LANGUAGES','Languages');
define('MSG_JETLAG','Time Difference');
define('MSG_INSTALL_SAME_DATE','The date and time must be the same as at home');
define('MSG_INSTALL_MYSQL_SERVER','MySQL Server');
define('MSG_INSTALL_MYSQL_PREFIX', "MySQL tables prefix");
define('MSG_INSTALL_MYSQL_DATABASE_NAME','MySQL database name');
define('MSG_INSTALL_MYSQL_LOGIN','MySQL database login');
define('MSG_INSTALL_PASS','MySQL database password');
define('MSG_INSTALL_TABLE_ALREADY_EXIST','--> This table already exists');
define('MSG_INSTALL_ALL_TABLE_ALREADY_EXIST','<strong><font color=#FF0000>The tables already exist. If you want to reinstall AllMyStats, you should first remove the tables.</font></strong>');
define('MSG_INSTALL_ONE_OR_TABLES_ALREADY_EXIST','<strong><font color=#FF0000>One or tables are already present, If you want to reinstall AllMyStats, you should first remove them.</font></strong>');
define('MSG_INSTALL_TABLES_CREATED_SUCCESS','The MySQL tables were created successfully');
define('MSG_INSTALL_COMPLETE','The installation is complete.');
define('MSG_BUTTON_NEXT_STEP', 'Procedure of your first connection'); // Next step
define('MSG_INSTALL_MYSQL_CONNEXION_ERROR','The test database connection to MySQL failed');
define('MSG_INSTALL_FORCE_INSTALL', "<strong>Or force installation</strong ><br>All tables MySQL AllMyStats will be removed and re-installed (is the same has a first installation)");
define('MSG_INSTALL_CANCEL_INSTALL', "Cancel the installation");

define('MSG_INSTALL_TIME_ZONE_SERVER', "Time zones of your server"); 
define('MSG_INSTALL_DATE_TIME_SERVER', "Date and time of your server :"); 
define('MSG_INSTALL_YOURPC_TIME', "Your PC displays :"); 

//-----------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------
define('MSG_NOTE_BAD_USER_AGENT', "<b>For the experts</b><br>
<b>Be careful not to define bots / user agent innocent!</b><br>
You can block them if necessary with file. htaccess<br><br>
The User Agent detected by this list or config_add.php (\$detect_bad_by_reverseDNS and \$BadIpList) <strong>are not counted as a visitor or as a robot</strong>.<br>
Type S : SPAM is red displayed in list Bad user agent.<br>
Type I  : Unknown.<br>
Type A : Other, to study.<br><br>
<b>Note:</b><br>
The user agent is compared to the same <strong>fully identical string</strong>.<br>
<br>If no bad user agent is detected from this list, the section Bad user agent will not be displayed.<br>
See also: <a href=\"http://www.user-agents.org/\" target=\"_blank\">user-agents.org : User agent list</a><br><br>");
//-----------------------------------------------------------------------------------------

//Add 23-09-2011
define('MSG_NOTE_ADD_BOT', "Note:<br />
							<strong>Lorsque mod-security est installé</strong>, l'ajout de certains bots peut être bloqué car détecté comme une attaque (ex : wget).<br />
							Pour contourner le problème il faut mettre dans \"Nom Robot\" le nom exact du robot mais dans les champs \"Nom parent\" et \"Commentaires\" ne pas inscrire le nom exact du robot.<br />
							Exemple pour wget: mettre wget_ pour les champs \"Nom parent\" et \"Commentaires\"<br />
							Eviter aussi les mots comme FTP, etc.");
//-------------------- stats_in -----------------------------------------------------------
define('MSG_WEBSITE_STATISTICS','Сайт статистики');
define('MSG_LAST_UPDATE','Последнее обновление:');
define('MSG_OPERATION_IN_PROGRESS','Работа в прогрессе, пожалуйста, подождите ...');
define('MSG_NOT_ALLOWED_THIS_SECTION','Вы не можете посетить этот раздел');
//-----------------------------------------------------------------------------------------

?>

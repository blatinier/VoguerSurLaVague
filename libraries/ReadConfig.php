<?php
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'prod'));
defined('APPLICATION_PATH') || define('APPLICATION_PATH', (getenv('APPLICATION_PATH') ? getenv('APPLICATION_PATH') : realpath(dirname(dirname(__FILE__)))));

// Get configuration
$cfg = parse_ini_file(APPLICATION_PATH . '/config/config.ini', true, INI_SCANNER_RAW);
$cfg = $cfg[APPLICATION_ENV];
$_activated_widgets = $cfg['widgets'];
?>

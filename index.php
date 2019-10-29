<?php
// Version
define('VERSION', '2.3.0.2');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}

//if (file_exists($li = DIR_APPLICATION.'/controller/extension/lightning/gamma.php')) require_once($li); //Lightning

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('catalog');
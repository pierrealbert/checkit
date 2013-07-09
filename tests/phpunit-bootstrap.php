<?php

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

define('APPLICATION_ENV', 'testing');

defined('APPLICATION_LOAD_TESTDATA')
    || define('APPLICATION_LOAD_TESTDATA', true);

require_once 'Zend/Application.php';
require_once 'Zend/Config/Ini.php';

// Set configuration
$config = new Zend_Config_Ini(
        APPLICATION_PATH . '/configs/application.ini',
        APPLICATION_ENV, true);

// Set configuration
$application = new Zend_Application(
    APPLICATION_ENV,
    $config
);

Zend_Session::$_unitTestEnabled = true;

// define host
$_SERVER['REMOTE_ADDR']     = '127.0.0.1';
$_SERVER['HTTP_HOST']       = 'zend-tests.com';
$_SERVER['SCRIPT_FILENAME'] = realpath(APPLICATION_PATH . '/../public/index.php');

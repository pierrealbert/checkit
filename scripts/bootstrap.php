<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path()
)));

require_once 'Zend/Application.php';
require_once 'Zend/Config/Ini.php';

// Set configuration
$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini',
        APPLICATION_ENV, true);
$localConfigPath = APPLICATION_PATH . '/configs/application.local.ini';
if (file_exists($localConfigPath)) {
    $localConfig = new Zend_Config_Ini(
            $localConfigPath,
            APPLICATION_ENV, true);    
    $config->merge($localConfig);
}

$application = new Zend_Application(
    APPLICATION_ENV,
    $config
);

$bootstrap = $application->getBootstrap();
$bootstrap->bootstrap('autoload');
$bootstrap->bootstrap('doctrine');

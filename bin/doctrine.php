<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path()
)));

/** Zend_Application */
require_once 'Zend/Application.php';
require_once 'Zend/Config/Ini.php';

// Set configuration
$config = new Zend_Config_Ini(
        APPLICATION_PATH . '/configs/application.ini',
        APPLICATION_ENV, true);
$localConfigPath = APPLICATION_PATH . '/configs/application.local.ini';
if (file_exists($localConfigPath)) {
    $localConfig = new Zend_Config_Ini(
            $localConfigPath,
            APPLICATION_ENV, true);    
    $config->merge($localConfig);
}

$config->resources->settings->modelClass = null;

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    $config
);

$config = $application->getOptions();

Zend_Controller_Front::getInstance()
    ->setParam('bootstrap', $application->getBootstrap());

try {
    $application->getBootstrap()->bootstrap('autoload');
    $application->getBootstrap()->bootstrap('frontController');
    $application->getBootstrap()->bootstrap('doctrine');
    $application->getBootstrap()->bootstrap('settings');

    $cli = new Doctrine_Cli($config['resources']['doctrine']);
    $cli->run($_SERVER['argv']);
    
} catch (Doctrine_Exception $e) {
    echo "\nDatabase Error:\n\n";    
    echo $e->getMessage();    
    echo "\n\nPlease check doctrine connection settings in application.ini (resources.doctrine.dsn)\n\n";    
} catch (Exception $e) {
    echo "\nApplication Error:\n\n";    
    echo $e->getMessage();
}

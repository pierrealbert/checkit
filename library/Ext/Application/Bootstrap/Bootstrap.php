<?php

/**
 * Zend_Application_Bootstrap_Bootstrap extension. Allow to set own config settings file for each resource
 * Example:
 *    resources.name.configPath  = "filePath/acl.ini"
 *    resources.name.configClass = "Zend_Config_Ini"
 *  
 * @category    Ext
 * @package     Ext_Application
 * @subpackage  Ext_Application_Bootstrap
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Application_Bootstrap_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        $moduleAutoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH
        ));

        $moduleAutoloader->addResourceTypes(array(
            'modelBase' => array(
                'namespace' => 'Model_Base',
                'path'      => 'models/Base'
            ),
            'core' => array(
                'namespace' => 'Core',
                'path'      => 'core'
            )
        ));
    }

    protected function _initZFDebug()
    {
        $this->bootstrap('frontController');
        $this->bootstrap('doctrine');

        $zfdebugConfig = $this->getOption('zfdebug');

        if (empty($zfdebugConfig['enabled'])) {
            return;
        }

        $options = array();

        if (isset($zfdebugConfig['params'])) {
            $options = $zfdebugConfig['params'];
        }

        $this->getResource('frontController')->registerPlugin(
            new ZFDebug_Controller_Plugin_Debug($options)
        );
    }
    
    /**
     * Set class state
     *
     * @param  array $options
     * @return Zend_Application_Bootstrap_BootstrapAbstract
     */
    public function setOptions(array $options)
    {
        $this->_options = $this->mergeOptions($this->_options, $options);

        $options = array_change_key_case($options, CASE_LOWER);
        $this->_optionKeys = array_merge($this->_optionKeys, array_keys($options));

        $methods = get_class_methods($this);
        foreach ($methods as $key => $method) {
            $methods[$key] = strtolower($method);
        }

        if (array_key_exists('pluginpaths', $options)) {
            $pluginLoader = $this->getPluginLoader();

            foreach ($options['pluginpaths'] as $prefix => $path) {
                $pluginLoader->addPrefixPath($prefix, $path);
            }
            unset($options['pluginpaths']);
        }

        foreach ($options as $key => $value) {
            $method = 'set' . strtolower($key);

            if (in_array($method, $methods)) {
                $this->$method($value);
            } elseif ('resources' == $key) {
                foreach ($value as $resource => $resourceOptions) {
                    if (isset($resourceOptions['configPath'])) {

                        $configClass = isset($resourceOptions['configClass'])
                            ? $resourceOptions['configClass']
                            : 'Zend_Config_Ini';

                        $configSection = isset($resourceOptions['configSection'])
                            ? $resourceOptions['configSection']
                            : $this->getEnvironment();

                        $config = new $configClass($resourceOptions['configPath'], $configSection, true);
                        $resourceOptions = array_merge($resourceOptions, $config->toArray());

                        unset($resourceOptions['configPath']);
                    }
                    $this->registerPluginResource($resource, $resourceOptions);
                }
            }
        }
        return $this;
    }
}

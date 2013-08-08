<?php

/**
 * Resource for settings application options. Application otions gettings from
 * database and from settings config file
 *
 * @category    Ext
 * @package     Ext_Application
 * @subpackage  Ext_Application_Resource
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Application_Resource_Settings extends Zend_Application_Resource_ResourceAbstract
{
    protected $_defaultOptions = array(
        'modelClass'    => 'Model_SettingsOption',
        'registryKey'   => 'Config_Settings'
    );

    /**
     *
     * @var Zend_Config
     */
    protected $_settings = null;

   /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Zend_Config
     */
    public function init()
    {
        $options = array_merge($this->_defaultOptions, $this->getOptions());

        $this->_config = new Zend_Config($this->getOptions(), true);

        if (isset($options['modelClass'])) {

            $this->getBootstrap()->bootstrap('doctrine');

            $customSettings = Doctrine::getTable($options['modelClass'])->findAll();

            $config = array();

            foreach ($customSettings as $settingsOption) {
                $configItem = &$config;

                $sections = explode('.', $settingsOption->name);

                foreach ($sections as $section) {
                    if (!isset($configItem[$section])) {
                        $configItem[$section] = array();
                    }
                    $configItem = &$configItem[$section];
                }
                $configItem = $settingsOption->value;
            }

            $this->_config->merge(new Zend_Config($config));
        }

        return $this->_config;
    }
}

<?php

class Plugin_Loader extends Zend_Controller_Plugin_Abstract
{
    protected $_plugins = array(
        'default'   => array(            
            'Plugin_HeadTitle',
            'Plugin_Auth'
        ),
        'admin'     => array(
            'Admin_Plugin_Auth'
        ),
        'user' => array(
            'User_Plugin_Auth'
        ),
    );

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $frontController  = Zend_Controller_Front::getInstance();
        $module = $this->getRequest()->getModuleName();

        if (isset($this->_plugins[$module])) {
            $modulePlugins = $this->_plugins[$module];

            foreach ($modulePlugins as $modulePlugin) {
                $frontController->registerPlugin(new $modulePlugin());
            }
        }
    }
} 
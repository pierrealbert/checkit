<?php

class Bootstrap extends Ext_Application_Bootstrap_Bootstrap
{
    /**
     *  Controller plugins initialization
     */
    protected function _initPlugins()
    {
        $this->bootstrap('autoload');

        $frontController = Zend_Controller_Front::getInstance();

        $frontController->registerPlugin(new Plugin_Loader())
                        ->registerPlugin(new Ext_Controller_Plugin_Layout())
                        ->registerPlugin(new Ext_Controller_Plugin_Navigation())
                        ->registerPlugin(new Ext_Controller_Plugin_ContextAction())
                        ->registerPlugin(new Ext_Controller_Plugin_ErrorHandler());
    }
}

<?php

class Default_Bootstrap extends Zend_Application_Module_Bootstrap
{
    protected function _initAutoload()
    {
        $moduleAutoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => realpath(dirname(__FILE__)),
        ));

        $moduleAutoloader->addResourceTypes(
            array('grids' => array(
                'namespace' => 'Grid',
                'path'      => 'grids',
            ))
        );
    }
    
    protected function _initHelpers()
    {
        Zend_Controller_Action_HelperBroker::addPath(
            realpath(dirname(__FILE__)) . '/controllers/helpers/',
            'Controller_Helper'
        );
    }
}

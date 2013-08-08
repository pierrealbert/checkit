<?php

/**
 * Layout plugin. Set custom layout based on module name
 *
 * @category    Ext_Controller_Plugin
 * @package     Common
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Controller_Plugin_Layout extends Zend_Controller_Plugin_Abstract
{
    /**
     * Set custom layout based on module name
     *
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $moduleName = $this->getRequest()->getModuleName();

        $modulePath = Zend_Controller_Front::getInstance()
            ->getModuleDirectory($moduleName);

        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath($modulePath . '/views/layouts');
    }
}

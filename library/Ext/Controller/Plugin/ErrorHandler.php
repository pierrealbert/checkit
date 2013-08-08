<?php

/**
 * ErrorHandler. Allow to have error controller for each module
 *
 * @category    Ext
 * @package     Ext_Controller
 * @subpackage  Ext_Controller_Plugin
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Controller_Plugin_ErrorHandler extends Zend_Controller_Plugin_Abstract
{
    /**
     * Sets error handler module
     *
     * @param Zend_Controller_Request_Abstract $request 
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $frontController = Zend_Controller_Front::getInstance();
        
        $errorPlugin = $frontController->getPlugin('Zend_Controller_Plugin_ErrorHandler');
        $errorPlugin->setErrorHandlerModule($request->getModuleName());
    }
}

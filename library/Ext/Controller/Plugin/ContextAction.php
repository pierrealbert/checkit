<?php

/**
 * ContextAction Plugin. 
 *
 * @category    Ext
 * @package     Ext_Controller
 * @subpackage  Ext_Controller_Plugin
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Controller_Plugin_ContextAction extends Zend_Controller_Plugin_Abstract
{
    const METHOD_PREFIX = 'ajax';

    /**
     * Check for AJAX calls. Disable layout rendering and clear view variable for AJAX calls
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if ($request->isXmlHttpRequest()) {
            Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer')
                ->view->clearVars();

            Zend_Controller_Action_HelperBroker::getStaticHelper('Layout')
                ->disableLayout();
        }
    }

    /**
     * Prevent access to ajax-only methods without AJAX call. All "ajax-only"
     * methods should have "ajax" prefix (ajax-get-product etc)
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        if (substr($request->getActionName(), 0, 4) == self::METHOD_PREFIX
                && !$request->isXmlHttpRequest())
        {            
            Zend_Controller_Action_HelperBroker::getStaticHelper('Error')
                ->notFound();
        }
    }
}

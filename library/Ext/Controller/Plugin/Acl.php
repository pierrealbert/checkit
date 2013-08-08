<?php

/**
 * ACL Plugin.
 *
 * @category    Ext
 * @package     Ext_Controller
 * @subpackage  Ext_Controller_Plugin
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    /**
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $resource   = Zend_Controller_Action_HelperBroker::getStaticHelper('Resource');
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
        $auth       = Zend_Controller_Action_HelperBroker::getStaticHelper('Auth');
        $error      = Zend_Controller_Action_HelperBroker::getStaticHelper('Error');

        $controller = $this->getRequest()->getControllerName();
        $action     = $this->getRequest()->getActionName();

        $acl = $resource->resource('acl');

        if (!$acl->has($controller)) {
            return false;
        }

        $role = Ext_Acl::DEFAULT_ROLE;

        if ($auth->isLoggedIn() && isset($auth->getCurrUser()->role)) {
            $role = $auth->getCurrUser()->role;
        }

        if (!$acl->isAllowed($role, $controller, $action)) {
            $error->noPermissions();
            return false;
        }
    }
}

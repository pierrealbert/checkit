<?php

class Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    /**
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $resource   = Zend_Controller_Action_HelperBroker::getStaticHelper('Resource');
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
        $auth       = Zend_Controller_Action_HelperBroker::getStaticHelper('Auth');

        $controller = $this->getRequest()->getControllerName();
        $action     = $this->getRequest()->getActionName();

        $acl = $resource->resource('acl');

        if (!$acl->has($controller)) {
            return false;
        }

        $role = 'guest';

        if ($auth->isLoggedIn() && isset($auth->getCurrUser()->role)) {
            $role = $auth->getCurrUser()->role;
        }

        if (!$acl->isAllowed($role, $controller, $action)) {
            $this->getRequest()
                    ->setControllerName('error')
                    ->setActionName('no-permissions');

            return false;
        }
    }
}

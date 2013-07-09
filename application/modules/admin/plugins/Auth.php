<?php

class Admin_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    protected $_publicResources = array(
        'login',
        'forgot-password'
    );

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $moduleName = $this->_request->getModuleName();

        if ($moduleName != 'admin') {
            return false;
        }


        $auth = Zend_Auth::getInstance();

        $loginUri = $moduleName .  '/login';

        if (!in_array($this->_request->getControllerName(), $this->_publicResources) && !$auth->hasIdentity()) {
            Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector')
                    ->gotoUrlAndExit($loginUri);
        }

        $this->_setViewVars();
    }

    protected function _setViewVars()
    {
        $auth           = Zend_Auth::getInstance();
        $viewRenderer   = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');

        $viewRenderer->view->isLoggedIn = false;
        $viewRenderer->view->currUser   = null;

        if ($auth->hasIdentity()) {
            $viewRenderer->view->isLoggedIn = true;
            $viewRenderer->view->currUser   = Doctrine::getTable('Model_Admin')->find($auth->getIdentity());
        }
    }
}

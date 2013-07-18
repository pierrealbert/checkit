<?php

class User_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_User'));
        
        $loginUri = '/login';

        if (!$auth->hasIdentity()) {

            if ($this->getRequest()->isGet()) {

                $referralUrl = new Zend_Session_Namespace('ReferralUrl');
                $referralUrl->url = $this->getRequest()->getRequestUri();
            }

            Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector')
                    ->gotoUrlAndExit($loginUri);
        }

        $this->_setViewVars();
    }

    protected function _setViewVars()
    {
        $auth         = Zend_Auth::getInstance();
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');

        $viewRenderer->view->isLoggedIn = false;
        $viewRenderer->view->currUser   = null;

        if ($auth->hasIdentity()) {
            $viewRenderer->view->isLoggedIn = true;
            $viewRenderer->view->currUser   = Doctrine::getTable('Model_User')->find($auth->getIdentity());
        }
    }
}

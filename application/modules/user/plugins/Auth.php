<?php

class User_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_User'));
        
        $loginUri = '/login';
        $currUser = null;
        
        if (!$auth->hasIdentity()) {

            if ($this->getRequest()->isGet()) {

                $referralUrl = new Zend_Session_Namespace('ReferralUrl');
                $referralUrl->url = $this->getRequest()->getRequestUri();
            }

            Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector')
                    ->gotoUrlAndExit($loginUri);
        } else {
            $currUser = Doctrine::getTable('Model_User')->find($auth->getIdentity());                
        }
        
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');

        $viewRenderer->view->isLoggedIn = $currUser ? true : false;
        $viewRenderer->view->currUser   = $currUser;
    }
}

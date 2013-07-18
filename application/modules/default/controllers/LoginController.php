<?php

class LoginController extends Zend_Controller_Action
{
    public function indexAction()
    {
        if ($this->_helper->auth->isLoggedIn()) {
            $this->_helper->redirector->gotoSimple('index', 'my-account', 'user');
        }
        
        $form = new Form_Login();

        $auth = Zend_Auth::getInstance();
        
        if ($this->getRequest()->isPost()
            && $form->isValid($this->getRequest()->getPost())
        ) {
            $authAdapter = new Ext_Auth_Adapter_Doctrine('Model_User', 'email');
            $authAdapter->setIdentity($this->getRequest()->getParam('email'));
            $authAdapter->setCredential($this->getRequest()->getParam('password'));

            if ($auth->authenticate($authAdapter)->isValid()) {
                $user = $authAdapter->getResultObject();
                $this->_autorize($user);
                $this->_helper->redirector->gotoSimple('residents', 'my-account', 'user');
            } else {
                $this->_helper->messenger->error('login_error_user');
                $this->_helper->redirector->gotoSimple('index', 'login', 'default');
            }
        }

        $this->view->form = $form;
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        
        Zend_Session::forgetMe();

        $this->_helper->messenger->success('logout_success');
        $this->_helper->redirector('index');
    }

    private function _autorize(Model_User $user)
    {
        $auth = Zend_Auth::getInstance();

        if ($user->isConfirmed() && $user->isActive()) {
            $auth->getStorage()->write($user->id);

            if ($this->getRequest()->getParam('remember') == 1) {

                $session = new Zend_Session_Namespace('Zend_User_RememberMe');
                $settings = $this->_helper->settings->getOptions();

                $session->setExpirationSeconds($settings['session']['expiration']);

                Zend_Session::rememberMe($settings['session']['remember']);
            }
        } else {
            $auth->clearIdentity();

            if (!$user->isConfirmed()) {
                $message = 'login_error_user_not_confirmed';
            }

            if (!$user->isActive()) {
                $message = 'login_error_user_account_blocked';
            }

            $this->_helper->messenger->error($message);
            $this->_helper->redirector->gotoSimple('index', 'login', 'default');
        }
    }
}

<?php

class Admin_LoginController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $form = new Admin_Form_Login();

        if ($this->getRequest()->isPost() &&
            $form->isValid($this->getRequest()->getPost()))
        {
            $auth = Zend_Auth::getInstance();

            $authAdapter = new Ext_Auth_Adapter_Doctrine('Model_Admin');
            $authAdapter->setIdentity($this->getRequest()->getParam('username'));
            $authAdapter->setCredential($this->getRequest()->getParam('password'));

            if ($auth->authenticate($authAdapter)->isValid()) {
                $admin = $authAdapter->getResultObject();
                if ($admin->isActive()) {
                    $auth->getStorage()->write($admin->id);
                    $this->_helper->redirector('index', 'index');
                } else {
                    $auth->clearIdentity();
                    $this->_helper->messenger->error('login_error_admin_account_blocked');
                }
            } else {
                $this->_helper->messenger->error('login_error_admin');
                $this->_helper->redirector('index', 'login');
            }
        }
        $this->view->form = $form;
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->messenger->success('logout_success');
        $this->_helper->redirector('index');
    }
}

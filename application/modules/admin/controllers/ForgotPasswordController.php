<?php

class Admin_ForgotPasswordController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $form = new Admin_Form_ForgotPassword();

        if ($this->getRequest()->isPost()
                && $form->isValid($this->getRequest()->getPost()))
        {
            $admin = Doctrine::getTable('Model_Admin')->findOneByEmail($this->_getParam('email', ''));

            if ($admin) {
                $admin->generateRestorePasswordKey();
                $admin->save();

                $this->_helper->mailer->send($admin->email, 'reset-password', $admin->toArray());

                $this->_helper->messenger->success('restore_password_message_was_sent');

            } else {
                $this->_helper->messenger->error('user_with_provided_login_or_email_is_not_found');
            }
        }

        $this->view->form = $form;
    }

    public function resetAction()
    {
        $admin = Doctrine::getTable('Model_Admin')->findOneByRestorePasswordKey($this->_getParam('key', ''));

        $form = new Admin_Form_ResetPassword();

        if ($this->_getParam('save') &&
            $form->isValid($this->getRequest()->getPost()))
        {
            $admin->restore_password_key = null;
            $admin->password             = $form->getValue('password');
            $admin->save();

            Zend_Auth::getInstance()->getStorage()->write($admin);
            
            $this->_helper->messenger->success('your_password_has_been_changed');

            $this->_redirect('admin/index');
        }

        $this->view->form    = $form;
        $this->view->admin   = $admin;
    }
}

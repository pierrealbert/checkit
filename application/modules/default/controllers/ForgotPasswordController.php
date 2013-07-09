<?php

class ForgotPasswordController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $form = new Form_ForgotPassword();

        if ($this->getRequest()->isPost()
                && $form->isValid($this->getRequest()->getPost()))
        {
            $user = Doctrine::getTable('Model_User')->findOneByEmail($this->_getParam('email', ''));

            if ($user) {
                $user->setRestorePasswordKey();
                $user->save();

                $this->_helper->mailer->send($user->email, 'reset-password', $user->toArray());

                $this->_helper->messenger->success('restore_password_message_was_sent');
            } else {
                $this->_helper->messenger->error('user_with_provided_email_is_not_found');
            }
        }
        $this->view->form = $form;
    }

    public function resetAction()
    {
        $user = Doctrine::getTable('Model_User')->findOneByRestorePasswordKey($this->_getParam('key', ''));

        $form = new Form_ResetPassword();
        
        if ($this->_getParam('save') &&
            $form->isValid($this->getRequest()->getPost()))
        {
            $user->restore_password_key = null;
            $user->password             = $form->getValue('password');
            $user->save();

            Zend_Auth::getInstance()->getStorage()->write($user->id);
            
            $this->_helper->messenger->success('your_password_has_been_changed');

            $this->_redirect('user/index');
        }
        
        $this->view->form = $form;
        $this->view->user = $user;
    }
}

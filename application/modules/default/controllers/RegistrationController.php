<?php

class RegistrationController extends Zend_Controller_Action
{
    protected function _processRegistrationForm(Zend_Form $form)
    {
        if ($this->getRequest()->isPost() 
                && $form->isValid($this->getRequest()->getPost()))
        {
            $user = Doctrine::getTable('Model_User')->create();
            
            $user->merge($form->getValues());            
            $user->generateConfirmRegistrationKey();       
            $user->save();          
            
            $this->_helper->mailer->send($user->email, 'confirm-registration', $user->toArray());
            $this->_helper->messenger->success('confirmation_email_message_was_sent');
            $this->_helper->redirector->gotoSimple('index', 'index', 'default');
        } 
        
    }
    
    public function indexAction()
    {
        $form = new Form_RegistrationType();

        if ($this->getRequest()->isPost()) {
            $this->_helper->redirector->gotoSimple('base', 'registration', 'default', array(
                'type' => $this->getRequest()->getParam('type')
            ));
        }

        $this->view->form = $form;
    }
    
    public function baseAction()
    {
        $type = $this->getRequest()->getParam('type');
        if (!Model_User::isTypeValid($type)) {
            $this->_helper->error->notFound();
        }
        $form = new Form_Registration();
        $form->setType($type);
        $this->_processRegistrationForm($form);

        $this->view->type = $type;
        $this->view->form = $form;        
    }

    public function proAction()
    {
        $form = new Form_RegistrationPro();

        $this->_processRegistrationForm($form);

        $this->view->form = $form;
    }
    
    public function confirmAction()
    {
        $user = Doctrine::getTable('Model_User')->findOneById($this->_getParam('id', ''));

        if (!$user) {
            $this->_helper->error->notFound();
        }
        
        if ($this->_getParam('key') == $user->confirm_registration_key) {
            $user->confirm();

            $auth = Zend_Auth::getInstance();
            $auth->getStorage()->write($user->id);

            $this->_helper->messenger->success('your_email_has_been_confirmed');
            
            $currUser = $this->_helper->auth->getCurrUser();
            if ($currUser->isResident() && !$currUser->hasResidents()) {
                $this->_helper->redirector->gotoSimple('residents', 'my-account', 'user');
            }
        } else {
            $this->_helper->messenger->error('wrong_confirmation_key');
        }
    }
}


<?php

class User_MyAccountController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $form = new User_Form_User();
        $form->populate($user->toArray());
               
        if ($this->getRequest()->isPost()
            && $form->isValid($this->getRequest()->getParams())
        ) {
            $user->merge($form->getValues());            
            $user->save();
            
            $this->_helper->messenger->success('your_account_succesfully_updated');
            $this->_helper->redirector('index', 'my-account', 'user');
        }
        $this->view->form = $form;
    }

    public function changePasswordAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $form = new User_Form_ChangePassword();
        
        if ($this->getRequest()->isPost()
            && $form->isValid($this->getRequest()->getParams())
        ) {
            $user->password = $this->_getParam('password');
            $user->save();

            $this->_helper->messenger->success('your_password_succesfully_updated');
            $this->_helper->redirector('change-password', 'my-account', 'user');
        }
        $this->view->form = $form;
    }

	public function residentsAction()
    {
		$session = new Zend_Session_Namespace('UserResidentRegistration');
		$count = !empty($session->residentsCount) ? $session->residentsCount : 1;

		$form = new User_Form_UserResident($count);

		$request = $this->_request;
		$availableTypes = Model_UserResident::getRentTypes();
		
		// rebuild form
		if ($request->isXmlHttpRequest()) {
			$type = $this->_getParam('type');
			if (in_array($type, $availableTypes)) {
				
				switch ($type) {
					case Model_UserResident::RENT_TYPE_COUPLE:
						$count = 2;
						break;
					case Model_UserResident::RENT_TYPE_ROOMMATE:
						$count = 5;
						break;
					default :
						$count = 1;
				}
				$form = new User_Form_UserResident($count);
				$session->residentsCount = $count;

				$this->_helper->json($form->render());
			}
		}
		
		if ($request->isPost() && $form->isValid($request->getPost()))
        {
			Zend_Debug::dump();
			exit;
			if (Zend_Session::namespaceIsset('UserResidentRegistration')) {
				Zend_Session::namespaceUnset('UserResidentRegistration');
			}
		}
		$this->view->assign(array(
			'availableTypes' => $availableTypes,
			'form' => $form
		));
	}
}
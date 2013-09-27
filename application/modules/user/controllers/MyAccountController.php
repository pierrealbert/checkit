<?php

class User_MyAccountController extends Zend_Controller_Action {

    public function indexAction() {
        $user = $this->_helper->auth->getCurrUser();

        $form = new User_Form_User();
        $form->populate($user->toArray());

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())
        ) {
            $user->merge($form->getValues());
            $user->save();

            $this->_helper->messenger->success('your_account_succesfully_updated');
            $this->_helper->redirector('index', 'my-account', 'user');
        }
        $this->view->form = $form;
    }

    public function changePasswordAction() {
        $user = $this->_helper->auth->getCurrUser();

        $form = new User_Form_ChangePassword();

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())
        ) {
            $user->password = $this->_getParam('password');
            $user->save();

            $this->_helper->messenger->success('your_password_succesfully_updated');
            $this->_helper->redirector('change-password', 'my-account', 'user');
        }
        $this->view->form = $form;
    }

    public function residentsAction() {
        $user = $this->_helper->auth->getCurrUser();
        //$session    = new Zend_Session_Namespace('UserResidentRegistration');
        $request = $this->_request;
        $selectedType = $user->getRentType();

        $residents = Doctrine::getTable('Model_UserResident')
                        ->findByUserId($user->id)->toArray();

        if ($request->isPost()) {
            if ($this->_getParam('member')) {
                $residents = $this->_getParam('member');
            }
            if ($this->_getParam('rent_type')) {
                $selectedType = $this->_getParam('rent_type');
            }
        }

        $count = $residents ? count($residents) : 1;

        $form = new User_Form_UserResident($count, $selectedType);

        $form->getSubForm("member_1")->getElement('resident_name')->setValue($user->first_name . ' ' . $user->last_name);

        $form->setResidents($residents);

        $availableTypes = Model_UserResident::getRentTypes();
        $roommateMaxCount = Model_UserResident::ROOMMATE_MAX_COUNT + 1;

        // rebuild form
        if ($request->isXmlHttpRequest()) {
            $type = $this->_getParam('type');
            if (in_array($type, $availableTypes)) {

                switch ($type) {
                    case Model_UserResident::RENT_TYPE_COUPLE:
                        $count = 2;
                        break;
                    case Model_UserResident::RENT_TYPE_ROOMMATE:
                        $param = (int) $this->_getParam('count');
                        $count = ($param > 0 && $param <= $roommateMaxCount) ? $param : 1;
                        break;
                    default :
                        $count = 1;
                }
                $form = new User_Form_UserResident($count, $type);
                $form->getSubForm("member_1")->getElement('resident_name')->setValue($user->first_name . ' ' . $user->last_name);
                $form->setResidents($residents);

                $this->_helper->json($form->render());
            }
        }

        // process form
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $data = $form->getValues();
            $members = $data['member'];
            $membersCount = count($members);

            for ($i = 1; $i <= $membersCount; $i++) {
                $userResident = Doctrine::getTable('Model_UserResident')->find($members[$i]['id']);
                if (!$userResident) {
                    $userResident = Doctrine::getTable('Model_UserResident')->create();
                }

                $userResident->merge($members[$i]);
                $userResident->user_id = $user->id;
                $userResident->rent_type = $selectedType;
                $userResident->is_primary = ($i === 1) ? 1 : 0;

                $userResident->save();
            }

            if (Zend_Session::namespaceIsset('UserResidentRegistration')) {
                Zend_Session::namespaceUnset('UserResidentRegistration');
            }

            $this->_helper->messenger->success('succesfully saved');
            $this->_helper->redirector('documents', 'my-account', 'user');
        }

        $this->view->assign(array(
            'availableTypes' => $availableTypes,
            'form' => $form,
            'selectedType' => $selectedType,
            'count' => $count,
            'roommateMaxCount' => $roommateMaxCount
        ));
    }

    public function documentsAction() {
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        $userId = Zend_Auth::getInstance()->getIdentity();
        $residents = Model_UserResidentTable::getInstance()->findByUserId($userId);
        $form = new User_Form_UserDocuments(array('residents' => $residents));
        $form->getValues();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $params = $request->getParams();

            preg_match('!\d+!', $params['fieldId'], $residentId);
            $residentId = $residentId[0];

            $adapter = new Zend_File_Transfer_Adapter_Http();
            $adapter->setDestination($settings->get('files.tmpPath'));

            if ($adapter->receive()) {

                //removing existing document
                $existingDocColl = Doctrine::getTable('Model_UserResidentDocument')->createQuery('doc')->select()
                        ->where('doc.user_resident_id=?', $residentId)
                        ->andWhere('doc.type=?', $params['docType'])
                        ->execute();
                $existingDoc = $existingDocColl->toArray();
                unlink($settings->get('files.basePath') . '/' . $existingDoc[0]['file']);
                $existingDocColl->delete();

                $fileInfo = $adapter->getFileInfo();

                $tmpPath = $settings->get('files.tmpPath') . '/' . $fileInfo['Filedata']['name'];
                $pathinfo = pathinfo($tmpPath);
                $filePath = $settings->get('files.document.path') . '/' . md5($fileInfo['Filedata']['name'] . time()) . '.' . strtolower($pathinfo['extension']);

                $fullFilePath = $settings->get('files.basePath') . '/' . $filePath;

                if (!is_file($tmpPath)) {
                    throw new Zend_Controller_Action_Exception($tmpPath . ' does not exist');
                }

                if (!@rename($tmpPath, $fullFilePath)) {
                    throw new Zend_Controller_Action_Exception('Can not move file to ' . $fullFilePath);
                }

                $documentModel = new Model_UserResidentDocument();
                $documentModel->file = $filePath;
                $documentModel->original_name = $pathinfo['filename'];
                $documentModel->user_resident_id = $residentId;
                $documentModel->type = $params['docType'];
                $documentModel->save();
                echo $settings->get('files.baseUrl') . '/' . $filePath;
                exit();
            }

            $this->_helper->messenger->success('files_was_uploaded');
            $this->_helper->redirector('index', 'my-account', 'user');
        }
        $this->view->form = $form;
    }

}

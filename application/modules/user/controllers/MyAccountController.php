<?php

class User_MyAccountController extends Core_Controller_Action_UserDashboard 
{
    
    protected $translator;

    public function init()
    {
        $this->translator = Zend_Registry::get('Zend_Translate');
        parent::init();
    }

    public function indexAction() 
    {
        $user = $this->_helper->auth->getCurrUser();

        if (!$user)
            $this->redirect($this->_helper->url('', 'login'));

        $form = new User_Form_User();
        $form->populate($user->toArray());

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())
        ) {
            $data = $form->getValues();
            $user->merge($data['profile']);
            
            if (!empty($data['change_email']['new_email'])) {
                $user->email = $data['change_email']['new_email'];
            }

            if (!empty($data['change_pass']['new_password'])) {
                $user->password = $data['change_pass']['new_password'];
            }
            
            $user->save();
            
            $this->_helper->messenger->success('your_account_succesfully_updaed');
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
        //$settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        $userId = Zend_Auth::getInstance()->getIdentity();
        $residents = Model_UserResidentTable::getInstance()->findByUserId($userId);
        $form = new User_Form_UserDocuments(array('residents' => $residents));
        

        if ($this->getRequest()->isPost()) {
            $groups = $form->getDisplayGroups();
            $data = array();
            foreach ($residents as $resident) {
                foreach ($resident->UserResidentDocument as $doc) {
                    $data[$doc->type . $resident->id] = $doc->file;
                }
            }


            $valid = true;

            foreach ($groups as $group) {
                $elements = $group->getElements();
                foreach ($elements as $elementKey => $val) {
                    if (empty($data[$elementKey]))
                        $valid = false;
                }
            }

            if ($valid) {
                $this->_helper->messenger->success('files_were_uploaded');
                $this->_helper->redirector('index', 'my-account', 'user');
            }
            $this->_helper->messenger->error('error_not_all_files_uploaded');
        }
        $this->view->form = $form;
    }

    public function documentsUploadAction() {
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
                if ($documentModel->save()) {
                    echo $settings->get('files.baseUrl') . '/' . $filePath;
                } else {
                    echo 'File upload error, please try again.';
                }

                exit();
            }
        }
    }

    public function myAdsAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $ads = Doctrine::getTable('Model_Property')->findByOwnerId($user->id);

        $this->view->ads = $ads;
    }

    public function myCandidatesAction()
    {
        $this->view->jQuery()->addOnLoad('initMyCandidates();');
        $user = $this->_helper->auth->getCurrUser();

        $ads = Doctrine::getTable('Model_Property')->getListByOwnerId($user->id);
        $applicationsPerProperty = array();
        foreach ($ads as $property) {
            $applicationsPerProperty[$property->id] = array('awaiting' => array(),
                                                            'accepted' => array());
        }
        foreach (Model_PropertyApplicationTable::getInstance()->getListByOwnerId($user->id) as $application) {
            if ($application->is_accepted) {
                $applicationsPerProperty[$application->PropertyVisitDates->property_id]['accepted'][] = $application;
            } else {
                $applicationsPerProperty[$application->PropertyVisitDates->property_id]['awaiting'][] = $application;
            }
        }
        
        $this->view->ads = $ads;
        $this->view->applicationsPerProperty = $applicationsPerProperty;
    }

    public function candidateAction()
    {
        $curUser = $this->_helper->auth->getCurrUser();
        
        $applicationId = $this->_getParam('application');
        $application = Model_PropertyApplicationTable::getInstance()->getOneByIdForPropOwner($applicationId, $curUser->id);
        if (!$application) {
            $this->_helper->messenger->success('application_not_found');
            $this->_helper->redirector('my-candidates', 'my-account', 'user');
        }

        $this->view->application = $application;
    }

    public function acceptCandidateAction()
    {
        $curUser = $this->_helper->auth->getCurrUser();
        
        $applicationId = $this->_getParam('application');
        $application = Model_PropertyApplicationTable::getInstance()->getOneByIdForPropOwner($applicationId, $curUser->id);
        if (!$application) {
            $this->_helper->messenger->error('application_not_found');
            $this->_helper->redirector('my-candidates', 'my-account', 'user');
        }
        if (!$application->is_accepted) {
            $application->is_accepted = True;
            $application->save();
            $this->_helper->messenger->success('application_accepted');
        } else {
            $this->_helper->messenger->success('application_already_accepted');
        }
        $this->_helper->redirector('my-candidates', 'my-account', 'user');
    }
    
    public function ajaxAcceptCandidateAction()
    {
        $curUser = $this->_helper->auth->getCurrUser();
        
        $errorMessage = ''; // if not null will be printed instead of form
        $successMessage = ''; // if not null will be printed instead of form
        $acceptForm = '';
        $applicationId = $this->_getParam('application');
        $application = Model_PropertyApplicationTable::getInstance()->getOneByIdForPropOwner($applicationId, $curUser->id);

        if (!$application) {
            $errorMessage = $this->translator->translate('application_not_existed');
        } elseif ($application->is_accepted) {
            $errorMessage = $this->translator->translate('application_already_accepted');
        } else {
            $acceptForm = new User_Form_AcceptApplication(array('applicationId' => $applicationId));
            if ($this->getRequest()->isPost() and $post = $this->getRequest()->getPost() and $acceptForm->isValid($post)) {
                $application->is_accepted = True;
                $application->save();
                $successMessage = 'application_accepted';
            } else {
                // Do nothing. If errorMessage and successMessage are empty,
                // then will be print the form with marked wrong fields
            }
        }

        $this->view->acceptForm = $acceptForm;
        $this->view->errorMessage = $errorMessage;
        $this->view->successMessage = $successMessage;
    }

    public function declineCandidateAction()
    {
        $curUser = $this->_helper->auth->getCurrUser();
        
        $applicationId = $this->_getParam('application');
        $application = Model_PropertyApplicationTable::getInstance()->getOneByIdForPropOwner($applicationId, $curUser->id);
        if (!$application) {
            $this->_helper->messenger->error('application_not_found');
            $this->_helper->redirector('my-candidates', 'my-account', 'user');
        }
        if (!$application->is_accepted) {
            $application->is_declined = True;
            $application->save();
            $this->_helper->messenger->success('application_declined');
        } else {
            $this->_helper->messenger->success('application_already_accepted');
        }
        $this->_helper->redirector('my-candidates', 'my-account', 'user');
    }

    public function ajaxRateCandidateAction()
    {
        $curUser = $this->_helper->auth->getCurrUser();
        
        $rate = $this->getRequest()->getPost('rate');
        $applicationId = $this->getRequest()->getPost('idBox');
        
        $application = Model_PropertyApplicationTable::getInstance()->getOneByIdForPropOwner($applicationId, $curUser->id);
        if (!$application) {
            $output = array('success' => 0);
        } else {
            $output = array('success' => 1,
                            'rate' => $rate,
                            'idBox' => $applicationId);
            $application->rate = (int)$rate;
            $application->save();
        }
        
        
        $this->getResponse()->setBody(Zend_Json::encode($output));
        
        if (null !== ($layout = Zend_Layout::getMvcInstance())) {
            $layout->disableLayout();
        }
        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);
    }
}

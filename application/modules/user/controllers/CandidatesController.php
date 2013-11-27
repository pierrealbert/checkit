<?php

class User_CandidatesController extends Core_Controller_Action_UserDashboard
{
    public function indexAction()
    {
        $this->view->jQuery()->addOnLoad('initMyCandidates();');
        $user = $this->_helper->auth->getCurrUser();

        $propertyId = intval($this->_getParam('property_id'));

        $properties = Doctrine::getTable('Model_Property')->getListByOwnerId($user->id);
        $applicationsPerProperty = array();
        foreach ($properties as $index => $property) {
            if ($propertyId > 0 && $propertyId != $property->id) {
                unset($properties[$index]);
                continue;
            }

            $applicationsPerProperty[$property->id] = array(
                'awaiting' => array(),
                'accepted' => array());
        }
        $userIDs = array();
        $cnt = 0;
        foreach (Model_PropertyApplicationTable::getInstance()->getListByOwnerId($user->id) as $application) {
            if ($propertyId > 0 && $propertyId != $application->property_id) continue;

            if ($application->rate > 5) $application->rate = 5;
            if ($application->is_accepted) {
                $applicationsPerProperty[$application->PropertyVisitDates->property_id]['accepted'][] = $application;
            } else {
                $applicationsPerProperty[$application->PropertyVisitDates->property_id]['awaiting'][] = $application;
            }
            if (!isset($userIDs[$application->visitor_id])) {
                $userIDs[$application->visitor_id] = $cnt++;
            }
        }

        $userIDs = array_flip($userIDs);
        list($this->view->residents, $residentIDs) = Model_UserResidentTable::getInstance()->getResidentsByIDs($userIDs);
        $this->view->garants = Model_UserResidentGarantTable::getInstance()->getResidentsGarantsByIDs($residentIDs);
        $this->view->ads = $properties;
        $this->view->applicationsPerProperty = $applicationsPerProperty;
    }

    public function viewAction()
    {
        $curUser = $this->_helper->auth->getCurrUser();
        
        $applicationId = $this->_getParam('application');
        $application = Model_PropertyApplicationTable::getInstance()->getOneByIdForPropOwner($applicationId, $curUser->id);
        if (!$application) {
            $this->_helper->messenger->success('application_not_found');
            $this->_helper->redirector('index', 'candidates', 'user');
        }

        $appsList = Model_PropertyApplicationTable::getInstance()->getListByOwnerId($curUser->id);
        $prevId   = false;
        $nextId   = false;
        $curId    = 0;
        foreach ($appsList as $index => $rec) {
            if ($curId === 0) {
                if ($applicationId != $rec->id) {
                    $curId = $rec->id;
                } else {
                    $curId = -1;
                }
            } else {
                if ($curId != -1) {
                    $prevId = $curId;
                }
                if ($curId === -1) {
                    $nextId = $rec->id;
                    break;
                }
                if ($applicationId == $rec->id) {
                    $curId = -1;
                } else {
                    $curId = $rec->id;
                }
            }
        }

        if ($application->rate > 5) $application->rate = 5;

        $this->view->contentTitle    = 'Mes candidats';
        $this->view->application     = $application;
        $this->view->residents       = $application->User->getResidents();
        $this->view->backToListUrl   = $this->view->url(array('action' => 'index', 'controller' => 'candidates', 'module' => 'user'), null, true);
        $this->view->backPrevItemUrl = ($prevId !== false ? $this->view->url(array('action' => 'view', 'controller' => 'candidates', 'module' => 'user', 'application' => $prevId), null, true) : '');
        $this->view->backNextItemUrl = ($nextId !== false ? $this->view->url(array('action' => 'view', 'controller' => 'candidates', 'module' => 'user', 'application' => $nextId), null, true) : '');
    }

    public function acceptCandidateAction()
    {
        $curUser = $this->_helper->auth->getCurrUser();

        $applicationId = $this->_getParam('application');
        $application = Model_PropertyApplicationTable::getInstance()->getOneByIdForPropOwner($applicationId, $curUser->id);
        if (!$application) {
            $this->_helper->messenger->error('application_not_found');
            $this->_helper->redirector('index', 'candidates', 'user');
        }
        if (!$application->is_accepted) {
            $application->is_accepted = True;
            $application->save();
            $this->_helper->messenger->success('application_accepted');
        } else {
            $this->_helper->messenger->success('application_already_accepted');
        }
        $this->_helper->redirector('index', 'candidates', 'user');
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
            $this->_helper->redirector('index', 'candidates', 'user');
        }
        if (!$application->is_accepted) {
            $application->is_declined = True;
            $application->save();
            $this->_helper->messenger->success('application_declined');
        } else {
            $this->_helper->messenger->success('application_already_accepted');
        }
        $this->_helper->redirector('index', 'candidates', 'user');
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
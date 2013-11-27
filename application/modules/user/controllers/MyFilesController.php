<?php
class User_MyFilesController extends Zend_Controller_Action
{
    protected $translator;

    public function init()
    {
        parent::init();

        $this->_helper->AjaxContext()
            ->addActionContext('ajax-infos-form-reload', 'html')
            ->addActionContext('ajax-garants-form-reload', 'html')

            ->initContext();

        $this->translator = Zend_Registry::get('Zend_Translate');
    }

    public function indexAction()
    {
        $this->view->headTitle('Publier une annonce');
        $this->view->contentTitle = 'Publier une annonce';
    }

    public function infosAction()
    {
        $this->view->headTitle('Publier une annonce', 'PREPEND');
        $this->view->contentTitle = 'Publier une annonce';

        $user = $this->_helper->auth->getCurrUser();
        $request = $this->getRequest();
        $selectedType = $user->getRentType();

        $residents = Doctrine::getTable('Model_UserResident')
            ->findByUserId($user->id)->toArray();

        $count = $residents ? count($residents) : 1;

        $form = new User_Form_UserResident($count, $selectedType);

        $form->getSubForm("member_1")->getElement('resident_name')->setValue($user->first_name . ' ' . $user->last_name);
        $form->getElement('phone')->setValue($user->phone);

        $form->setResidents($residents);

        $availableTypes = Model_UserResident::getRentTypes();
        $roommateMaxCount = Model_UserResident::ROOMMATE_MAX_COUNT;

        $this->view->assign(array(
            'availableTypes' => $availableTypes,
            'form' => $form,
            'selectedType' => $selectedType,
            'count' => $count,
            'roommateMaxCount' => $roommateMaxCount
        ));
    }

    public function ajaxInfosFormReloadAction()
    {
        $user = $this->_helper->auth->getCurrUser();
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
        $form->getElement('phone')->setValue($user->phone);

        $form->setResidents($residents);

        $availableTypes = Model_UserResident::getRentTypes();
        $roommateMaxCount = Model_UserResident::ROOMMATE_MAX_COUNT + 1;

        // rebuild form
        if ($request->isXmlHttpRequest()) {

            $type = $this->_getParam('type');

            if (in_array($type, $availableTypes)) {
                $isRoommate = 0;

                switch ($type) {
                    case Model_UserResident::RENT_TYPE_COUPLE:
                        $count = 2;
                        break;
                    case Model_UserResident::RENT_TYPE_ROOMMATE:
                        $param = (int) $this->_getParam('count');
                        $count = ($param > 0 && $param <= $roommateMaxCount) ? $param : 1;
                        $isRoommate = 1;
                        break;
                    default :
                        $count = 1;
                }
                $form = new User_Form_UserResident($count, $type);
                $form->getSubForm("member_1")->getElement('resident_name')->setValue($user->first_name . ' ' . $user->last_name);
                $form->setResidents($residents);

            }

            $this->view->form = $form;
        }

        $this->view->assign(array(
            'availableTypes' => $availableTypes,
            'form' => $form,
            'selectedType' => $selectedType,
            'count' => $count,
            'roommateMaxCount' => $roommateMaxCount,
            'isRoommate' => $isRoommate
        ));

        $this->_helper->layout()->disableLayout();
    }

    public function ajaxInfosFormSubmitAction()
    {
        $user = $this->_helper->auth->getCurrUser();
        $request = $this->getRequest();
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
        $roommateMaxCount = Model_UserResident::ROOMMATE_MAX_COUNT;

        $result = array();

        // form validation
        if ($request->isPost() && $form->isValid($request->getPost())) {
            // clear second residents
            /*$oldResidents = Doctrine::getTable('Model_UserResident')->findBy('user_id', $user->id);
            if ($oldResidents->count() >= 2) {
                foreach ($oldResidents as $oldResident) {
                    if ($oldResident->is_primary != 1) {
                        $oldResident->delete();
                    }
                }
            }*/
            // form is valid
            $result['status'] = 'success';

            $data = $form->getValues();
            $members = $data['member'];

            $membersCount = count($members);

            for ($i = 1; $i <= $membersCount; $i++) {
                if ($members[$i]['id'] == '') {
                    unset($members[$i]['id']);
                    $userResident = Doctrine::getTable('Model_UserResident')->create();
                } else {
                    $userResident = Doctrine::getTable('Model_UserResident')->find($members[$i]['id']);
                    if (!$userResident) {
                        $userResident = Doctrine::getTable('Model_UserResident')->create();
                    }
                }

                if ($members[$i]['monthly_income_guaranteed'] == '') {
                    $members[$i]['monthly_income_guaranteed'] = null;
                }

                $userResident->merge($members[$i]);
                $userResident->user_id = $user->id;
                $userResident->rent_type = $selectedType;
                $userResident->is_primary = ($i === 1) ? 1 : 0;
                $userResident->save();
            }

            // save user phone
            $user->phone = $data['phone'];
            $user->save();

            $result['message'] = "Resident infos is saved";

        } else {
            // form not valid - return error messages
            $result['status'] = 'errors';
            $result['data'] = $form->getMessages();
        }

        $this->_helper->json($result);

        $this->_helper->layout()->disableLayout();
    }

    public function garantsAction()
    {
        $this->view->headTitle('Publier une annonce', 'PREPEND');
        $this->view->contentTitle = 'Publier une annonce';

        $user = $this->_helper->auth->getCurrUser();

        $residents = Doctrine::getTable('Model_UserResident')->findBy('user_id', $user->id);

        $form = array();
        if ($residents->count()) {
            foreach ($residents as $resident) {
                $garants = Doctrine::getTable('Model_UserResidentGarant')->findBy('user_resident_id', $resident->id);
                $garantsCount = $garants->count() ? $garants->count() : 1;
                $form[$resident->id] = new User_Form_UserResidentGarant($garantsCount, $resident);
                $form[$resident->id]->setGarants($garants);
            }
        }

        $this->view->assign(array(
            'form' => $form,
            'residents' => $residents,
        ));

    }

    public function ajaxGarantsFormReloadAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $residents = Doctrine::getTable('Model_UserResident')->findBy('user_id', $user->id);

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $residentId = $this->_getParam('residentId');
            // create garant
            $garantFirstData = array(
                'user_resident_id' => (int) $residentId,
            );
            $garant = Doctrine::getTable('Model_UserResidentGarant')->create($garantFirstData);
            $garant->save();
        }

        $form = array();
        if ($residents->count()) {
            foreach ($residents as $resident) {
                $garants = Doctrine::getTable('Model_UserResidentGarant')->findBy('user_resident_id', $resident->id);
                $garantsCount = $garants->count() ? $garants->count() : 1;
                $form[$resident->id] = new User_Form_UserResidentGarant($garantsCount, $resident);
                $form[$resident->id]->setGarants($garants);
            }
        }

        $this->view->assign(array(
            'form' => $form,
            'residents' => $residents,
        ));

        $this->_helper->layout()->disableLayout();
    }

    public function ajaxGarantsFormSubmitAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $residents = Doctrine::getTable('Model_UserResident')->findBy('user_id', $user->id);

        $form = array();
        if ($residents->count()) {
            foreach ($residents as $resident) {
                $garants = Doctrine::getTable('Model_UserResidentGarant')->findBy('user_resident_id', $resident->id);
                $garantsCount = $garants->count() ? $garants->count() : 1;
                $form[$resident->id] = new User_Form_UserResidentGarant($garantsCount, $resident);
                $form[$resident->id]->setGarants($garants);

                if ($this->getRequest()->isPost()) {
                    if ($form[$resident->id]->isValid($this->getRequest()->getPost())) {
                        $data = $form[$resident->id]->getValues();
                        if (isset($data['garant'][$resident->id])) {

                            $results = $data['garant'][$resident->id];
                            foreach ($results as $garantData) {
                                if ($garantData['garant'] == 'yes') {
                                    $garantRecord = Doctrine::getTable('Model_UserResidentGarant')->find($garantData['id']);
                                    if (!$garantRecord) {
                                        $garantRecord = Doctrine::getTable('Model_UserResidentGarant')->create();
                                    }

                                    unset($garantData['id']);
                                    unset($garantData['garant']);

                                    $garantRecord->merge($garantData);
                                    $garantRecord->save();

                                    $result['status'] = 'success';
                                    $result['message'] = "Garants is saved";
                                }
                            }
                        }
                    } else {
                        // form not valid - return error messages
                        $result['status'] = 'errors';
                        $result['data'][$resident->id] = $form[$resident->id]->getMessages();
                    }
                }

            }

            $result['residents'] = $residents->toArray();
        }


        $this->_helper->json($result);

        $this->_helper->layout()->disableLayout();
    }

    public function verificationAction()
    {
        $this->view->headTitle('Publier une annonce', 'PREPEND');
        $this->view->contentTitle = 'Publier une annonce';

    }

    public function documentsAction()
    {
        $this->view->headTitle('Publier une annonce', 'PREPEND');
        $this->view->contentTitle = 'Publier une annonce';

        $user = $this->_helper->auth->getCurrUser();

        $residents = Doctrine::getTable('Model_UserResident')->findBy('user_id', $user->id);
        $form = new
        /*$data = array();
        if ($residents->count()) {
            foreach ($residents as $resident) {
                $data[$resident->id] = $resident->toArray();
                $garants = Doctrine::getTable('Model_UserResidentGarant')->findBy('user_resident_id', $resident->id);
                if ($garants->count()) {
                    foreach ($garants as $garant) {
                        $data[$resident->id]['garants'][$garant->id] = $garant->toArray();
                        $documents = Doctrine::getTable('Model_UserResidentDocument')->findBy('user_resident_garant_id', $garant->id);
                        if ($documents->count()) {
                            foreach ($documents as $document) {
                                $data[$resident->id]['garants'][$garant->id]['documents'][$document->id] = $document->toArray();
                            }
                        }
                    }
                }
            }
        }

        $this->view->data = $data;*/

        $this->view->residents = $residents;


        /*$documents = Doctrine::getTable('Model_UserResidentDocument')
            ->findByUserId($user->id)
            ->toArray();

        if ($this->getRequest()->isPost()) {
            if ($this->_getParam('doc')) {
                $documents = $this->_getParam('doc');
            }
        }

        $count = $documents ? count($documents) : 1;

        $form = new User_Form_UserResidentDocument($count);

        $form->setDocuments($documents);

        $types = Model_UserResidentDocument::getTypes();

        $this->view->assign(array(
            'types' => $types,
            'form' => $form,
            'count' => $count,
        ));*/

    }

    public function ajaxDocumentsFormReloadAction()
    {

    }

    public function ajaxDocumentsFormSubmitAction()
    {

    }

}
<?php

class User_OwnerServicesController extends Core_Controller_Action_UserDashboard
{

    public function indexAction()
    {

    }    
    
    public function energyAction()
    {
        
    }
    
    public function stateOfPlayAction()
    {
        
    }
    
    public function mailTemplatesAction()
    {
        
    }
    
    public function contractTemplatesAction()
    {
        
    }
    
    public function professionalsAction()
    {
        $requestId = $this->getRequest()->getParam('request_id');
        $request = Model_UserRequestTable::getInstance()->find($requestId);
        $form = new User_Form_OwnerServices();
        $typesList = Model_UserRequest::getRequestsTypeList($this->_currUser->type);

        $this->view->readonly = 0;

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getParams();

            $errors = false;

            if (!in_array($data['request_type'], array_keys(Model_UserRequest::getRequestsTypeList($this->_currUser->type)))) {
                $data['request_type'] = 1;
            }
            $data['visit_spec'] = trim($data['visit_spec']);
            if ($data['request_type'] == 1 && ($data['visit_spec'] == '' || !in_array($data['visit_spec'], array_keys(Model_UserRequest::getSpecialisationsList())))) {
                $form->getElement('visit_spec')->addErrors(array('Select correct problem type.'))->markAsError();
                $errors = true;
            }

            if (!preg_match('#^[0-9]{2}/[0-9]{2}/[0-9]{4}$#', $data['availability']) ||
                !preg_match('#^[0-9]{2}:[0-9]{2}:[0-9]{2}$#', $data['visit_time_begin']) ||
                !preg_match('#^[0-9]{2}:[0-9]{2}:[0-9]{2}$#', $data['visit_time_end'])) {

                $form->getElement('visit_time_end')->addErrors(array('Select correct date and time.'))->markAsError();
                $errors = true;
            }

            if ($errors) {
                $form->populate($data);
            } else {
                $request = new Model_UserRequest;
                $request->request = serialize($data);
                $request->user_id = $this->_currUser->id;
                $request->save();

                $this->_helper->messenger->success('request_was_sended');
                $this->_helper->redirector->gotoSimple('index', $this->_currUser->type.'-services', 'user');
            }
        } else {
            if ($request) {
                $data = @unserialize($request->request);
                $form->populate($data);
                $this->view->readonly = 1;
            }
        }

        $this->view->form = $form;
        $this->view->typesList = $typesList;
        $this->view->headLink()->appendStylesheet('/assets/css/chosen/chosen.css');
    }        
}
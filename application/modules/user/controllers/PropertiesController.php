<?php

class User_PropertiesController extends Core_Controller_Action_UserDashboard 
{    
    public function init()
    {
        parent::init();
    }
    
    public function indexAction()
    {
        $propertyModel = Model_PropertyTable::getInstance();
        $this->view->activeProperties   = $propertyModel->findActiveByIserId($this->_currUser->id);
        $this->view->inActiveProperties = $propertyModel->findInActiveByIserId($this->_currUser->id);
    }
}
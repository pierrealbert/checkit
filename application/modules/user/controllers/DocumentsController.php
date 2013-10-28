<?php

class User_DocumentsController extends Core_Controller_Action_UserDashboard
{
    public function indexAction()
    {
        $this->view->residents    = $this->_currUser->getResidents();
    }    
}
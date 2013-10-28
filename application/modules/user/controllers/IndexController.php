<?php

class User_IndexController extends Core_Controller_Action_UserDashboard
{
    /**
     * @var $_currUser Model_User
     */
    protected $_currUser = null;

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $this->view->contentTitle   = 'account_dashboard';
        $this->view->searches       = $this->_currUser->getLatestSearches();
        $this->view->applications   = $this->_currUser->getLatestApplications();
    }  
}


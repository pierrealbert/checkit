<?php

class Core_Controller_Action_UserDashboard extends Zend_Controller_Action
{   
    /**
     *
     * @var type Model_User
     */
    protected $_currUser = null;

    public function init() 
    {
        parent::init();
        $this->_currUser = $this->_helper->auth->getCurrUser();       
        $this->view->assign('contentTitle', 'account_dashboard');
    }

    public function postDispatch() 
    {                        
        if (!$this->getRequest()->isXmlHttpRequest()) {
            $this->view->assign('showBottomSidebar', true);
            $this->view->assign('dashboardContent', $this->view->render($this->getViewScript()));
            $this->renderScript('_partials/dashboard.phtml');

            parent::postDispatch();
        }
    }           
}
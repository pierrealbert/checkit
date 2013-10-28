<?php

class User_CandidaturesController extends Core_Controller_Action_UserDashboard
{
    /**
     * @var $_currUser Model_User
    */
    protected $_currUser = null;

    public function init()
    {
        parent::init();
        $this->_helper->AjaxContext()
            ->addActionContext('ajax-check-status', 'html')
            ->addActionContext('ajax-remove-declined', 'json')

            ->initContext();

    }

    public function indexAction()
    {
        /**
         * @var $applications Model_PropertyApplication
         */
        $applications = $this->_currUser->getApplications();
        $this->view->applications = $applications;

        $this->view->acceptedAppsCount = $this->_currUser->getAcceptedAppsCount();
        $this->view->declinedAppsCount = $this->_currUser->getDeclinedAppsCount();
    }

    public function ajaxCheckStatusAction()
    {
        if ($this->getRequest()->isPost()) {

            $id = (int) $this->_request->getParam('app_id');

            /**
             * @var $application Model_PropertyApplication
             */
            $application = Model_PropertyApplicationTable::getInstance()->find($id);
            $this->view->application = $application;

            $this->_helper->layout()->disableLayout();
        }
    }

    public function ajaxRemoveDeclinedAction()
    {
        if ($this->getRequest()->isPost()) {
            if ($this->_request->getParam('remove') == 'remove') {
                /**
                 * @var $declinedApps Model_PropertyApplication
                */
                $declinedApps = $this->_currUser->getDeclinedApplications();
                // remove this applications
                /**
                 * @var $application Model_PropertyApplication
                */
                $result = array();
                foreach ($declinedApps as $application) {
                    $result[] = $application->id;
                    $application->delete();
                }
                return $this->_helper->json->sendJson(array('result' => $result, 'message' => 'Declined object removed'));
            }
        }
        $this->_helper->layout()->disableLayout();
    }
}
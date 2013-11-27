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
        $dates  = Model_PropertyApplicationTable::getInstance()->getVisitDatesByOwnerId($this->_currUser->id);
        $alerts = Model_AlertTable::getInstance()->getAlertsByUserId($this->_currUser->id);

        $visitDates = array();
        foreach ($dates as $indx => $rec) {
            list($y,$m,$d) = explode('-', $rec['availability']);

            $visitDates[] = array(
                'year' => intval($y),
                'month' => intval($m),
                'day' => intval($d),
                'declined' => $rec['is_declined']
            );
        }

        $this->view->contentTitle   = 'account_dashboard';
        $this->view->visitDates     = $visitDates;
        $this->view->alerts         = $alerts;
        $this->view->searches     = $this->_currUser->getLatestSearches();
        $this->view->applications = $this->_currUser->getLatestApplications();

        if ($this->_currUser->type == Model_User::OWNER) {
            $propertyModel = Model_PropertyTable::getInstance();
            $this->view->activeProperties   = $propertyModel->findActiveByIserId($this->_currUser->id);
            $this->view->inActiveProperties = $propertyModel->findInActiveByIserId($this->_currUser->id);
            $this->view->candidatesCount = Model_PropertyApplicationTable::getInstance()->getApplicationsAttrByOwnerId($this->_currUser->id);
            //$this->view->candidats = $this->_currUser->getLatestApplications();
        }
    }

    public function closeAlertAction() {


    }
}


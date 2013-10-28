<?php

class User_SearchesController extends Core_Controller_Action_UserDashboard
{
    public function indexAction()
    {
        $this->view->favorites      = $this->_currUser->getFavorites();
        $this->view->searches       = $this->_currUser->getLatestSearches();
        $this->view->propertyType   = Model_Property::getTypes();
    }    
    
    public function deleteAction()
    {
        $search = Model_SearchTable::getInstance()->find(
            (int) $this->getRequest()->getParam('id')
        );
        
        if (!$search || ($search->user_id != $this->_currUser->id)) {
            $this->_helper->error->notFound();
        }        
        
        $search->delete();
        
        $return = $this->_getParam('return') ? $this->_getParam('return') : 'index';
        
        $this->_helper->redirector->gotoSimple('index', $return, 'user');
    }
}
<?php

class Admin_MessagesController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->view->grid = new Admin_Grid_Messages;
    }
    
    public function viewAction()
    {
        $id = $this->_getParam('id');

        $message = Doctrine::getTable('Model_UserMessage')->find($id);

        if (!$message) {
            $this->_helper->error->notFound();
        }
        
        $this->view->message = $message;
    }    
}

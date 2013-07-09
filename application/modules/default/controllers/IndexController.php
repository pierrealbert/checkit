<?php

class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_helper->messenger->success('welcome');
        $this->_helper->messenger->success('how_are_you');

        $this->view->grid = new Grid_Array();
    }
}


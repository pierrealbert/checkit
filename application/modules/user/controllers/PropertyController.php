<?php

class User_PropertyController extends Zend_Controller_Action
{
    public function postAdAction()
    {
        $form = new User_Form_DescribeYourSelf();

        if ($this->getRequest()->isPost()
            && $form->isValid($this->getRequest()->getParams())
        ) {

            $this->_helper->redirector('well-rental', 'property', 'user');
        }

        $this->view->form = $form;
    }

    public function wellRentalAction()
    {
        $form = new User_Form_WellRental();        

        if ($this->getRequest()->isPost()
            && $form->isValid($this->getRequest()->getParams())
        ) {
            $this->_helper->redirector('well-description-of-property', 'property', 'user');
        }

        $this->view->jQuery()->addStylesheet('/css/ui/jquery-ui-1.8.14.css');

        $this->view->form = $form;
    }

    public function wellDescriptionOfPropertyAction()
    {
        $form = new User_Form_WellDescriptionOfProperty();        

        if ($this->getRequest()->isPost()
            && $form->isValid($this->getRequest()->getParams())
        ) {

            $this->_helper->redirector('well-description-of-property', 'property', 'user');
        }

        $this->view->form = $form;
    }
}


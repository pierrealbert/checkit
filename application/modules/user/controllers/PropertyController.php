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
        $user = $this->_helper->auth->getCurrUser();

        $property = $this->getProperty($user);

        $form = new User_Form_WellRental();        

        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getParams())) {

                $data = $form->getValues();

                if (!$property) { // On create
                    $property = Doctrine::getTable('Model_Property')->create();
                
                    $data = $property->setOnCreateDefaults($data, $user);
                }
            
                $property->merge($data);

                $property->save();

                $this->_helper->redirector('well-description-of-property', 'property', 'user', array('item' => $property->id));
            }
        } elseif ($this->getRequest()->isGet() && $property) { // Fill form for edit

                $form->populate($property->toArray());
        }

        $this->view->jQuery()->addStylesheet('/css/ui/jquery-ui-1.8.14.css');

        $this->view->form = $form;
    }

    public function wellDescriptionOfPropertyAction()
    {
        $user = $this->_helper->auth->getCurrUser();
        
        $property = $this->getProperty($user);

        if (!$property) {

            $this->_helper->redirector('well-rental', 'property', 'user');
        }

        $form = new User_Form_WellDescriptionOfProperty();        

        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getParams())) {

                $property->merge($form->getData());

                $property->save();

                $this->_helper->redirector('well-description-of-property', 'property', 'user', array('item' => $property->id));
            }
        } else {

            $form->initData($property->toArray());
        }

        $this->view->property = $property;

        $this->view->form = $form;
    }

    private function getProperty($user)
    {
        $property = Doctrine::getTable('Model_Property')->findOneById($this->_getParam('item', ''));

        if ($property && $property->owner_id != $user->id) {

            $this->_helper->redirector('well-rental', 'property', 'user');
        }

        return $property;
    }
}


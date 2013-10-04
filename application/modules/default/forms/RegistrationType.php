<?php

class Form_RegistrationType extends Ext_Form
{
    protected function _addElements()
    {
        $this->addElement('radio', 'type', array(
            'label'         => 'signup_type',
            'required'      => true,
            'multiOptions'  => Model_User::getTypes(),
            'value'         => Model_User::OWNER,
        ));


        $this->addElement('submit', 'register', array(
            'label' => 'signup_by_email',
            'class' => 'btn btn-blue'
        ));
    }
    
    public function init()
    {
        $this->setAttrib('id', 'registration-form');
        
        $this->setAction($this->getView()->url(array(
            'controller' => 'registration',
            'action'     => 'index'
        ), null, true));

        $this->_addElements();       
    }
}

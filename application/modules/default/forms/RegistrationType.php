<?php

class Form_RegistrationType extends Ext_Form
{
    protected function _addElements()
    {
        $this->setAttrib('id', 'registration-form');
        
        $this->addElement('radio', 'type', array(
            'label'         => 'signup_type',
            'required'      => true,
            'multiOptions'  => Model_User::getTypes(),
            'value'         => Model_User::OWNER,
        ));


        $this->addElement('submit', 'register', array(
            'label' => 'signup_by_email'
        ));
    }
    
    public function init()
    {
        $this->setAttrib('id', 'form-registration');

        $this->_addElements();       
    }
}

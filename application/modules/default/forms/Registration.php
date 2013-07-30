<?php

class Form_Registration extends Ext_Form
{
    protected function _addElements($withSubmit = True)
    {
        $this->addElement('text', 'first_name', array(
            'label'    => 'first_name',
            'required' => true,
            'filters'  => array('StringTrim')
        ));

        $this->addElement('text', 'last_name', array(
            'label'    => 'last_name',
            'required' => true,
            'filters'  => array('StringTrim')
        ));
        
        $this->addElement('text', 'email', array(
            'label'      => 'email',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
                array(
                    'entityNotExists',
                    true,
                    array('Model_User', 'email')
                )
            )
        ));

        $this->addElement('password', 'password', array(
            'label'         => 'password',
            'required'      => true,
            'filters'       => array('StringTrim'),
            'autocomplete'  => 'off',
            'validators'    => array(
                array('StringLength', false, array(
                    'min' => 4
                )
            ))
        ));

        $this->addElement('password', 'confirm_password', array(
            'label'        => 'confirm_password',
            'required'     => true,
            'filters'      => array('StringTrim'),
            'autocomplete' => 'off',
            'validators'   => array (
                new Zend_Validate_Identical('password')
            )
        ));

        if ($withSubmit) {
            $this->addElement('submit', 'register', array(
                'label' => 'Create Account'
            ));
        }
    }
    
    public function init()
    {
        $this->setAttrib('id', 'form-registration');

        $this->_addElements();
       
    }
}

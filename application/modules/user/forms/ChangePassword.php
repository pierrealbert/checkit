<?php

class User_Form_ChangePassword extends Ext_Form
{
    public function init()
    {
        $this->setAttrib('id', 'form-change_password');

        $this->addElement('password', 'password', array(
            'label'        => 'password',
            'required'     => true,
            'filters'      => array('StringTrim'),
            'autocomplete' => 'off',
            'validators'   => array(array('StringLength', false, array(
                'min' => 4
            )))
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
        
        $this->addElement('submit', 'change', array(
            'label' => 'change',
            'class' => 'ui-state-default ui-corner-all'
        ));
    }
}

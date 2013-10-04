<?php

class Form_ResetPassword extends Ext_Form
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('password', 'password', array(
            'label'      => 'password',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'Alnum'
            )
        ));

        $this->addElement('password', 'confirm', array(
            'label'      => 'confirm_password',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array (
                new Zend_Validate_Identical('password')
            )
        ));

        $this->addElement('submit', 'save', array(
            'label'    => 'save',
            'class'    => 'btn btn-blue ui-state-default ui-corner-all'
        ));
    }
}
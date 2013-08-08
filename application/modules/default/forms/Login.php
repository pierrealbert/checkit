<?php

class Form_Login extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('text', 'email', array(
            'label'      => 'email',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress'
            )
        ));

        $this->addElement('password', 'password', array(
            'label'      => 'password',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));

        $this->addElement('checkbox', 'remember', array(
            'label'      => 'remember_me'
        ));

        $this->addElement('submit', 'login', array(
            'label'    => 'sign_in',
            'class'    => 'ui-state-default ui-corner-all'
        ));
    }
}
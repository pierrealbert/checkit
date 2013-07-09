<?php

class Admin_Form_Login extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('text', 'username', array(
            'label'      => 'username',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'Alnum'
            )
        ));

        $this->addElement('password', 'password', array(
            'label'      => 'password',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));

        $this->addElement('submit', 'login', array(
            'label'    => 'login',
            'class'    => 'ui-state-default ui-corner-all'
        ));
    }
}
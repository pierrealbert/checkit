<?php

class Form_Login extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'login-form');
        $this->setAction($this->getView()->url(array(
            'controller' => 'login',
            'action'     => 'index'
        ), null, true));

        $this->addElement('text', 'email', array(
            'label'      => 'email',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress'
            ),
            'class'    => 'input-mail'
        ));

        $this->addElement('password', 'password', array(
            'label'      => 'password',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'class'    => 'input-pass'
        ));

        $this->addElement('checkbox', 'remember', array(
            'label'      => 'remember_me'
        ));

        $this->addElement('submit', 'login', array(
            'label'    => 'sign_in',
            'id'       => 'login-button',
            'class'    => 'btn btn-blue'
        ));
    }
}
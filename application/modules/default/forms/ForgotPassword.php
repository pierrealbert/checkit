<?php

class Form_ForgotPassword extends Ext_Form
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

        $this->addElement('captcha', 'captcha', array(
            'ignore'  => true,
            'label'   => 'captcha',
            'captcha' => array(
                'captcha'   => 'Image',
                'wordLen'   => 4,
                'width'     => 160,
                'timeout'   => 120,
                'expiration'=> 300,
                'font'      => 'fonts/arial.ttf',
                'imgDir'    => 'images/captcha/',
                'imgUrl'    => '/images/captcha/',
                'gcFreq'    => 5
            )
        ));
  
        $this->addElement('submit', 'send', array(
            'required' => false,
            'label'    => 'send',
            'class'    => 'btn btn-blue'
        ));
    }
}
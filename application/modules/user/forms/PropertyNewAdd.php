<?php


class User_Form_PropertyNewAdd extends Ext_Form
{
    public function init()
    {
        $this->addElement('submit', 'begin', array(
            'class'     => 'btn btn-red btn-arrow-next right'
        ));
    }
}
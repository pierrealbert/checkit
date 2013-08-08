<?php


class User_Form_PropertyNewAdd extends Ext_Form
{
    public function init()
    {
        $this->addElement('submit', 'start', array(
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
}
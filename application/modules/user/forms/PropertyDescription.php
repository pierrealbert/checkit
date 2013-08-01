<?php


class User_Form_PropertyDescription extends Ext_Form
{
    public function init()
    {
        $this->addElement('submit', 'next', array(
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
}
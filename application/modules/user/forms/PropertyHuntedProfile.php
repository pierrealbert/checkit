<?php


class User_Form_PropertyHuntedProfile extends Ext_Form
{
    public function init()
    {
        $this->addElement('submit', 'next', array(
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
}
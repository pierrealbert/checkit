<?php

class User_Form_WellRental extends Ext_Form
{
    public function init()
    {
        $this->addElement('submit', 'next', array(
            'label'     => 'next',
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
}
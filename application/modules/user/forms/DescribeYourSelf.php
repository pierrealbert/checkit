<?php

class User_Form_DescribeYourSelf extends Ext_Form
{
    public function init()
    {
        $this->addElement('submit', 'next', array(
            'label'     => 'next',
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
}
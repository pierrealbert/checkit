<?php


class User_Form_PropertyUnPublishAd extends Ext_Form
{
    public function init()
    {
        $this->addElement('submit', 'unpublish', array(
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
}
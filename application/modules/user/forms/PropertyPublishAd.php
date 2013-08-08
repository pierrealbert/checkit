<?php


class User_Form_PropertyPublishAd extends Ext_Form
{
    public function init()
    {
        $this->addElement('submit', 'publish', array(
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
}
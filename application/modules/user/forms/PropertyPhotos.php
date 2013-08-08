<?php


class User_Form_PropertyPhotos extends Ext_Form
{
    public function init()
    {
        $this->addElement('hidden', 'form', array(
            'value' => 'next_step'
        ));

        $this->addElement('submit', 'next', array(
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
}
<?php

class Admin_Form_PageContent extends Ext_Form
{
    public function init()
    {
        $this->setAttrib('id', 'form-product');

        $this->addElement('hidden', 'id', array());

        $this->addElement('text', 'title', array(
            'label'     => 'title',
            'required'  => true,
            'filters'   => array('StringTrim')
        ));

        $this->addElement('tinyMCE', 'content', array(
            'id'        => 'page-content',
            'label'     => 'content',
            'required'  => true,
            'rows'      => 20,
            'cols'      => 80,
            'class'     => 'mceSimple',
            'filters'   => array('StringTrim'),
            'tinyMCE' => array(
                'mode'  => 'textareas',
                'theme' => 'advanced'
            )
        ));

        $this->addElement('submit', 'save', array(
            'label'     => 'save',
            'class'     => 'ui-state-default ui-corner-all'
        ));
        $this->addElement('submit', 'cancel', array(
            'label'     => 'cancel',
            'class'     => 'ui-state-default ui-corner-all'
        ));

        $this->addDisplayGroupButtons(array('save', 'cancel'));
    }
}

<?php

class Admin_Form_File extends Ext_Form
{
    public function init()
    {
        parent::init();

        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $this->addElement('file', 'file', array(
            'label'         => 'upload_file',
            'required'      => true,
            'destination'   => $settings->get('files.tmpPath'),
            'filters' => array(
                new Ext_Filter_File_UniqueName(array(
                    'targetDir' => $settings->get('files.tmpPath')
                ))
            )
        ));

        $this->addElement('submit', 'save', array(
            'label' => 'upload'
        ));
    }
}

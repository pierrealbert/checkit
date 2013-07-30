<?php

class User_Form_WellUploadPhotos extends Ext_Form
{
    public function init()
    {
        parent::init();

        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $this->addElement('file', 'image', array(
            'label'         => 'upload_image',
            'required'      => true,
            'destination'   => $settings->get('propertyImages.tmpPath'),
            'filters' => array(
                new Ext_Filter_File_UniqueName(array(
                    'targetDir' => $settings->get('propertyImages.tmpPath')
                ))
            )
        ));

        $this->addElement('submit', 'upload', array(
            'label'     => 'upload',
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
}
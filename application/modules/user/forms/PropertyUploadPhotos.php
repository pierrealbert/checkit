<?php


class User_Form_PropertyUploadPhotos extends Ext_Form
{
    public function init()
    {
        $this->addElement('hidden', 'form', array(
            'value' => 'upload'
        ));

        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $this->addElement('file', 'image', array(
            'label'         => 'Téléchargement des photos',
            'required'      => true,
            'destination'   => $settings->get('propertyImages.tmpPath'),
            'filters' => array(
                new Ext_Filter_File_UniqueName(array(
                    'targetDir' => $settings->get('propertyImages.tmpPath')
                ))
            )
        ));

        $this->addElement('submit', 'upload', array(
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
}
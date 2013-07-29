<?php

class User_Form_WellUploadPhotos extends Ext_Form
{
    public function init()
    {
        $image = new Ext_Form_Element_FileImage('image');

        $this->addElement($image, 'image');

        $this->addElement('submit', 'upload', array(
            'label'     => 'upload',
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
}
<?php

class Form_SaveSearch extends Ext_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'form-save-search');
        
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        
        $this->addElement('text', 'name', array(
            'label'    => 'name',
            'required' => true,
            'filters'  => array('StringTrim')
        ));
        
        $this->addElement('submit', 'save', array(
            'label'    => 'save search',
        ));
	}

}

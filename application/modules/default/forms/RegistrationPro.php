<?php

class Form_RegistrationPro extends Form_Registration
{
    protected function _addElements($withSubmit = True)
    {
        $this->addElement('text', 'company_name', array(
            'label'    => 'company_name',
            'required' => true,
            'filters'  => array('StringTrim')
        ));
        
        $this->addElement('text', 'company_siret', array(
            'label'    => 'company_siret',
            'required' => true,
            'filters'  => array('StringTrim')
        ));
        
        $this->addElement('text', 'company_address', array(
            'label'    => 'company_address',
            'required' => true,
            'filters'  => array('StringTrim')
        ));
        
        $this->addElement('text', 'company_zip', array(
            'label'    => 'company_zip',
            'required' => true,
            'filters'  => array('StringTrim')
        ));
        
        $this->addElement('text', 'company_city', array(
            'label'    => 'company_city',
            'required' => true,
            'filters'  => array('StringTrim')
        ));
        
        parent::_addElements($withSubmit);
    }
}

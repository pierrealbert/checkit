<?php

class User_Form_WellRental extends ZendX_JQuery_Form
{
    public function init()
    {
        $this->addElement('text', 'amount_of_rent_excluding_charges', array(
            'label'      => 'amount_of_rent_excluding_charges',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));

        $this->addElement('text', 'amount_of_charges', array(
            'label'      => 'amount_of_charges',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));

        $this->addElement('radio', 'is_furnished', array(
            'label'      => 'is_furnished',
            'required'   => true,
            'multiOptions' => array(
                0 => "Empty",
                1 => "Furnished",
            ),
            'value' => 0,
        ));

        $this->addElement('text', 'deposit', array(
            'label'      => 'deposit',
            'filters'    => array('StringTrim')
        ));

        $elem = new ZendX_JQuery_Form_Element_DatePicker(
                "availability", array("label" => "Availability:")
            );

        $elem->setJQueryParams(array(
                'dateFormat'  => 'yy-mm-dd',
                'defaultDate' => time('Y-m-d'),
                'minDate'     => 0,
            ));
        
        $this->addElement($elem);
        
        $this->addElement('submit', 'next', array(
            'label'     => 'next',
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }

    public function isValid($data)
    {
        if (isset($data['availability']) && !empty($data['availability'])) {
            $validator = new Zend_Validate_Date();

            if (!$validator->isValid($data['availability'])) {

            }
        }

        return parent::isValid($data);
    }
}
<?php

class User_Form_WellRental extends ZendX_JQuery_Form
{
    public function init()
    {
        // -------------------------------------------------------------------

        $this->addElement('text', 'amount_of_rent_excluding_charges', array(
            'label'      => 'amount_of_rent_excluding_charges',
            'filters'    => array(new Ext_Filter_Money()),
            'allowEmpty' => false,
            'value'      => 0.0,
            'validators' => array(new Zend_Validate_GreaterThan(array('min' => 0))),
            
        ));
        // -------------------------------------------------------------------

        $this->addElement('text', 'amount_of_charges', array(
            'label'      => 'amount_of_charges',
            'filters'    => array(new Ext_Filter_Money()),
            'allowEmpty' => false,
            'value'      => 0.0,
            'validators' => array(new Zend_Validate_GreaterThan(array('min' => 0))),
        ));
        // -------------------------------------------------------------------

        $this->addElement('radio', 'is_furnished', array(
            'label'      => 'is_furnished',
            'required'   => true,
            'multiOptions' => array(
                0 => "Empty",
                1 => "Furnished",
            ),
            'value' => 0,
        ));
        // -------------------------------------------------------------------

        $this->addElement('text', 'deposit', array(
            'label'      => 'deposit',
            'filters'    => array(new Zend_Filter_Int()),
        ));
        // -------------------------------------------------------------------
        //TODO: Set error message

        $elem = new ZendX_JQuery_Form_Element_DatePicker(
                "availability", 
                array(
                    "label"      => "Availability:",
                    "allowEmpty" => false,
                    'validators' => array(new Zend_Validate_Date()),
                )
            );

        $elem->setJQueryParams(array(
                'dateFormat'  => 'yy-mm-dd',
                'defaultDate' => time('Y-m-d'),
                'minDate'     => 0,
            ));
        
        $this->addElement($elem);
        // -------------------------------------------------------------------
        
        $this->addElement('submit', 'next', array(
            'label'     => 'next',
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
}
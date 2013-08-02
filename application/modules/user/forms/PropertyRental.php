<?php


class User_Form_PropertyRental extends ZendX_JQuery_Form
{
    public function init()
    {
        $this->addElement('text', 'title', array(
            'label'      => 'title',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));

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

        $this->addElement('select','lease_duration',  array(
            'label'        => 'lease_duration',
            'value'        => 1,
            'multiOptions' => array(
                1 => "1 mois",
                2 => "2 mois",
                3 => "3 mois",
                4 => "4 mois",
                5 => "5 mois",
                6 => "6 mois",
            ),
        ));

        // -------------------------------------------------------------------
        
        $this->addElement('radio', 'deposit', array(
            'label'      => 'deposit',
            'required'   => true,
            'multiOptions' => array(
                1 => "1 mois",
                2 => "2 mois",
            ),
            'value' => 1,
        ));

        // -------------------------------------------------------------------

        $elem = new ZendX_JQuery_Form_Element_DatePicker(
                "availability", 
                array(
                    "label"      => "Availability:",
                    "allowEmpty" => false,
                    'validators' => array(new Zend_Validate_Date('yyyy-MM-dd')),
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
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
}
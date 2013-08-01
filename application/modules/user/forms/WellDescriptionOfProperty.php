<?php

class User_Form_WellDescriptionOfProperty extends Ext_Form
{
    public function init()
    {
        $multiCheckboxses = self::getMultiCheckboxses();

        $this->addElement('text', 'address', array(
            'label'      => 'address',
            'filters'    => array('StringTrim'),
            'required'   => true,
        ));

        $this->addElement('text', 'postcode', array(
            'label'      => 'postcode',
            'attribs'     => array('maxlength' => 5),
            'filters'    => array('StringTrim'),
            'allowEmpty' => false,
            'validators'   => array (
                new Zend_Validate_PostCode('fr_FR')
            )
        ));

        $this->addElement('text', 'city', array(
            'label'      => 'city',
            'filters'    => array('StringTrim'),
            'required'   => true,
        ));

        $this->addElement('text', 'size', array(
            'label'      => 'size',
            'filters'    => array(new Ext_Filter_Float()),
            'allowEmpty' => false,
            'value'      => 0.0,
            'validators' => array(new Zend_Validate_GreaterThan(array('min' => 0))),
            
        ));

    	$this->addElement('radio', 'property_type', array(
			'label'        => 'property_type',
			'required'     => true,
    		'separator'    => '',
            'multiOptions' => Model_Property::getTypes(),
		));

        $this->addElement('radio', 'number_of_rooms1', array(
            'label'      => 'number_of_rooms1',
    		'separator'    => '',
            'multiOptions' => Model_Property::getNumberOfRooms1Info(),
        ));

        $this->addElement('radio', 'number_of_rooms2', array(
            'label'      => 'number_of_rooms2',
    		'separator'    => '',
            'multiOptions' => Model_Property::getNumberOfRooms1Info(),
        ));

        $this->addElement('text', 'floor', array(
            'label'      => 'floor',
            'filters'    => array('StringTrim'),
            'required'   => true,
        ));

        $this->addElement('checkbox', 'is_lift', array(
            'label'          => 'is_lift',
            'uncheckedValue' => '0',
            'checkedValue'   => '1'
        ));

        $this->addElement('multiCheckbox', 'decor', array(
            'label'          => 'decor',
            'multiOptions' => $multiCheckboxses['decor'],
    		'separator' => '',
        ));

        $this->addElement('multiCheckbox', 'outhouse', array(
            'label'          => 'outhouse',
            'multiOptions' => $multiCheckboxses['outhouse'],
    		'separator' => '',
        ));
        
        $this->addElement('multiCheckbox', 'outdoor_space', array(
            'label'         => 'outdoor_space',
            'multiOptions'  => $multiCheckboxses['outdoor_space'],
            'separator'     => '',
        ));

        $this->addElement('multiCheckbox', 'building', array(
            'label'         => 'building',
            'multiOptions'  => $multiCheckboxses['building'],
            'separator'     => '',
        ));

        $this->addElement('radio', 'number_of_bathrooms', array(
            'label'      => 'number_of_bathrooms',
    		'separator'    => '',
            'multiOptions' => Model_Property::getNumberOfBathroomsInfo(),
        ));

        $this->addElement('multiCheckbox', 'heating_system', array(
            'label'         => 'heating_system',
            'multiOptions'  => $multiCheckboxses['heating_system'],
            'separator'     => '',
        ));

        $this->addElement('submit', 'next', array(
            'label'     => 'next',
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }

}
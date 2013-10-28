<?php


class User_Form_PropertyEdit extends ZendX_JQuery_Form
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
            'allowEmpty' => false,
            'validators' => array(new Zend_Validate_GreaterThan(array('min' => 0))),
            
        ));
        // -------------------------------------------------------------------

        $this->addElement('text', 'amount_of_charges', array(
            'label'      => 'amount_of_charges',
            'allowEmpty' => false,
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

        $this->addElement('multiCheckbox', 'planning', array(
            'label'          => 'planning',
            'multiOptions' => $multiCheckboxses['planning'],
            'separator' => '',
        ));

        $this->addElement('multiCheckbox', 'outbuilding', array(
            'label'          => 'outbuilding',
            'multiOptions' => $multiCheckboxses['outbuilding'],
            'separator' => '',
        ));
        
        $this->addElement('multiCheckbox', 'exterior', array(
            'label'         => 'exterior',
            'multiOptions'  => $multiCheckboxses['exterior'],
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
//----------------------------------------------------//

        $huntedMultiCheckboxses = self::getHuntedMultiCheckboxses();

        $this->addElement('multiCheckbox', 'hunted_profile', array(
            'label'         => 'hunted_profile',
            'multiOptions'  => $huntedMultiCheckboxses['hunted_profile'],
            'separator'     => '',
        ));

        $this->addElement('checkbox', 'is_roomate', array(
            'label'          => 'is_roomate',
            'uncheckedValue' => '0',
            'checkedValue'   => '1'
        ));

        $this->addElement('submit', 'next', array(
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }

    public static function getHuntedMultiCheckboxses()
    {
        return array(
            'hunted_profile' => array(
                'is_r_student'     => 'student',
                'is_r_employee'    => 'employee',
                'is_r_independent' => 'independent',
                'is_r_other'       => 'other',
            ),
        );
    }
    public static function getMultiCheckboxses()
    {
        return Model_Property::getValuesGroups();
    }

    private function initMultiCheckboxses($data)
    {
        $multiCheckboxses = self::getMultiCheckboxses();

        foreach ($multiCheckboxses as $elementName => $itemsList) {
            $data[$elementName] = array();

            foreach ($itemsList as $key => $value) {
                if (1 == $data[$key]) {
                    $data[$elementName][] = $key;
                }
            }
        }

        return $data;
    }

    private function extractMultiCheckboxses($data)
    {
        $multiCheckboxses = self::getMultiCheckboxses();

        foreach ($multiCheckboxses as $elementName => $itemsList) {

            foreach ($itemsList as $key => $value) {
                $data[$key] = 0;

                if (in_array($key, $data[$elementName])) {
                    $data[$key] = 1;
                }
            }
        }
        return $data;
    }

    public function initData($data)
    {
        if (0 == $data['property_type']) { // Set default
            $data['property_type'] = Model_Property::TYPE_APARTMENT;
        }

        $this->populate($this->initMultiCheckboxses($data));
    }

    public function getData()
    {
        $data = $this->getValues();

        return $this->extractMultiCheckboxses($data);
    }
}
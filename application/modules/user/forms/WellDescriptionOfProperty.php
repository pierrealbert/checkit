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

        $this->addElement('text', 'area', array(
            'label'      => 'area',
            'filters'    => array('StringTrim'),
            'required'   => true,
        ));

    	$this->addElement('radio', 'property_type', array(
			'label'        => 'property_type',
			'required'     => true,
    		'separator'    => '',
            'multiOptions' => Model_Property::getTypes(),
		));

        $this->addElement('multiCheckbox', 'type_comments', array(
            'multiOptions' => $multiCheckboxses['type_comments'],
    		'separator' => '',
        ));

        $this->addElement('text', 'number_of_rooms', array(
            'label'      => 'number_of_rooms',
            'filters'    => array('StringTrim'),
            'required'   => true,
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

        $this->addElement('text', 'number_of_bathrooms', array(
            'label'      => 'number_of_bathrooms',
            'filters'    => array('StringTrim'),
            'required'   => false,
        ));

        $this->addElement('multiCheckbox', 'outdoor_space', array(
            'label'      => 'outdoor_space',
            'multiOptions' => $multiCheckboxses['outdoor_space'],
    		'separator' => '',
        ));

        $this->addElement('multiCheckbox', 'property_other', array(
            'label'      => 'property_other',
            'multiOptions' => $multiCheckboxses['property_other'],
    		'separator' => '',
        ));

        $this->addElement('submit', 'next', array(
            'label'     => 'next',
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }

    private static function getMultiCheckboxses()
    {
        return array(
            'type_comments' => array(
                'is_old'         => 'Old',
                'is_refurbished' => 'Refurbished',
                'is_stone'       => 'Stone',
                'is_floor'       => 'Floor',
                'is_molding'     => 'Molding',
                'is_double_glazing' => 'Double glazing',
                'is_storage'     => 'Storage',
            ),
            'outdoor_space' => array(
                'is_balcony' => 'Balcony',
                'is_terrace' => 'Terrace',
                'is_garden'  => 'Garden',
            ),
            'property_other' => array(
                'is_guard'    => 'Guard',
                'is_attic'    => 'Attic',
                'is_basement' => 'Basement',
                'is_garage'   => 'Garage',
            ),
        );
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
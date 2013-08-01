<?php


class User_Form_PropertyDescription extends Ext_Form
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
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }

    public static function getMultiCheckboxses()
    {
        return array(
            'decor' => array(
                'is_separate_restrooms' => 'Separate restrooms',
                'is_parquet_floor'      => 'Parquet floor',
                'is_molding'            => 'Molding',
                'is_double_glazing'     => 'Double glazing',
                'is_storage_area'       => 'Storage area',
                'is_fireplace'          => 'Fireplace',
            ),

            'outhouse' => array(
                'is_attic'          => 'Attic',
                'is_basement'       => 'Basement',
                'is_parking_lot'    => 'Parking lot',
                'is_garage'         => 'Garage',
                'is_swimming_pool'  => 'Swimming pool',
            ),

            'outdoor_space' => array(
                'is_balcony' => 'Balcony',
                'is_terrace' => 'Terrace',
                'is_garden'  => 'Garden',
                'is_yard'    => 'Yard',
            ),

            'building' => array(
                'is_digicode'       => 'Digicode / Interphone',
                'is_watchman'       => 'Watchman',
                'is_old_building'   => 'Old Building',
                'is_very_old_building' => 'Very old building',
                'is_renove'         => 'Renove',
            ),
            
            'heating_system' => array(
                'is_individuel' => 'Individuel',
                'is_central'    => 'Central / Collectif',
                'is_au_sol'     => 'Au sol',
                'is_gaz'        => 'Gaz',
                'is_electrique' => 'Electrique',
                'is_autre'      => 'Autre',
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
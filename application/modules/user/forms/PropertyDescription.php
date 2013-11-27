<?php


class User_Form_PropertyDescription extends Ext_Form
{
    public function init()
    {
        $multiCheckboxses = self::getMultiCheckboxses();

        $this->addElement('text', 'address', array(
            'label'      => 'address',
            'style'      => 'width: 500px;',
            'filters'    => array('StringTrim'),
            'required'   => true,
        ));

        $this->addElement('text', 'postcode', array(
            'label'      => 'postcode',
            'attribs'     => array('maxlength' => 5),
            'filters'    => array('StringTrim'),
            'allowEmpty' => false,
            'required'   => true,
            'validators'   => array (
                new Zend_Validate_PostCode('fr_FR')
            )
        ));

        $this->addElement('text', 'city', array(
            'label'      => 'city',
            'filters'    => array('StringTrim'),
        ));

        $this->addElement('text', 'size', array(
            'label'      => 'size',
            'filters'    => array(new Ext_Filter_Float()),
            'allowEmpty' => false,
            'value'      => '',
            'required'   => true,
            'validators' => array(new Zend_Validate_GreaterThan(array('min' => 0))),
            
        ));

    	$this->addElement('radioButtons', 'property_type', array(
			'label'        => 'property_type',
			'required'     => true,
    		'separator'    => '',
            'multiOptions' => Model_Property::getTypes(),
		));

        $this->addElement('radioButtons', 'number_of_rooms1', array(
            'label'      => 'number_of_rooms1',
    		'separator'    => '',
            'multiOptions' => Model_Property::getNumberOfRooms1Info(),
        ));

        $this->addElement('radioButtons', 'number_of_rooms2', array(
            'label'      => 'number_of_rooms2',
    		'separator'    => '',
            'multiOptions' => Model_Property::getNumberOfRooms2Info(),
        ));

        $this->addElement('text', 'floor', array(
            'label'      => 'floor',
            'filters'    => array('StringTrim'),
            'required'   => true,
            'validators' => array(new Zend_Validate_Int()),
        ));

        $this->addElement('radioButtons', 'is_lift', array(
            'label'          => 'is_lift',
            'required'     => true,
            'multiOptions' => array(
                '0' => 'no',
                '1' => 'yes'
            ))
        );

        $chbox = new Zend_Form_Element_MultiCheckbox('planning');
        $chbox->setSeparator('')
            ->setLabel('planning')
            ->addMultiOptions($multiCheckboxses['planning'])
            ->setDecorators(array(
                array('MchBox', array('labelClass' => 'btn-input-lite', 'needAll' => false)),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-black')),
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
            ));
        $this->addElement($chbox);

        $chbox = new Zend_Form_Element_MultiCheckbox('outbuilding');
        $chbox->setSeparator('')
            ->setLabel('outbuilding')
            ->addMultiOptions($multiCheckboxses['outbuilding'])
            ->setDecorators(array(
                array('MchBox', array('labelClass' => 'btn-input-lite', 'needAll' => false)),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-black')),
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
            ));
        $this->addElement($chbox);

        $chbox = new Zend_Form_Element_MultiCheckbox('exterior');
        $chbox->setSeparator('')
            ->setLabel('exterior')
            ->addMultiOptions($multiCheckboxses['exterior'])
            ->setDecorators(array(
                array('MchBox', array('labelClass' => 'btn-input-lite', 'needAll' => false)),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-black')),
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
            ));
        $this->addElement($chbox);

        $chbox = new Zend_Form_Element_MultiCheckbox('building');
        $chbox->setSeparator('')
            ->setLabel('building')
            ->addMultiOptions($multiCheckboxses['building'])
            ->setDecorators(array(
                array('MchBox', array('labelClass' => 'btn-input-lite', 'needAll' => false)),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-black')),
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
            ));
        $this->addElement($chbox);

        $this->addElement('radioButtons', 'number_of_bathrooms', array(
            'label'      => 'number_of_bathrooms',
    		'separator'    => '',
            'multiOptions' => Model_Property::getNumberOfBathroomsInfo(),
        ));

        $chbox = new Zend_Form_Element_MultiCheckbox('heating_system');
        $chbox->setSeparator('')
            ->setLabel('heating_system_type')
            ->addMultiOptions($multiCheckboxses['heating_system'])
            ->setDecorators(array(
                array('MchBox', array('labelClass' => 'btn-input-lite', 'needAll' => false)),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-black')),
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
            ));
        $this->addElement($chbox);


        $this->addElement('submit', 'next', array(
            'class'     => 'ui-state-default ui-corner-all'
        ));
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
<?php

class Form_SearchStandard extends Ext_Form
{
    protected function _addJS() 
    {
        $br = "\n";
        $this->getView()->jQuery()->addOnload(
            'initSearchStandard();' . $br
        );
    }
    
    public function init()
    {
        $this->_addJS();
        $this->setMethod('post');
        $this->setAction($this->getView()->url(array(
            'controller' => 'search',
            'action'     => 'standard'
        ), null, true));
        
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $regionBlockOptions = array('' => 'all');
        foreach (Model_RegionBlockTable::getInstance()->getAllWithDiscricts() as $region) {
            $regionBlockOptions[$region->id] = $region->RegionDistrict->name . ' - ' . $region->name;
        }
		$this->addElement('select', 'region_block_id', array(
            'label'      => 'location',
			'multiOptions' => $regionBlockOptions,
        ));

        $this->addElement('text', 'min_budget', array(
            'label'      => 'min_budget',
            'filters'    => array('StringTrim', 'StripTags'),
            'decorators' => array('ViewHelper'),
            'placeholder' => 'Budget min. (en €)'
        ));

        $this->addElement('text', 'max_budget', array(
            'label'      => 'max_budget',
            'filters'    => array('StringTrim', 'StripTags'),
            'decorators' => array('ViewHelper'),
            'placeholder' => 'Budget max. (en €)'
        ));

        $this->addElement('text', 'min_size', array(
            'label'      => 'min_size',
            'filters'    => array('StringTrim', 'StripTags'),
            'decorators' => array('ViewHelper'),
            'placeholder' => 'Surface min. (en m²)'
        ));

        $this->addElement('text', 'max_size', array(
            'label'      => 'max_size',
            'filters'    => array('StringTrim', 'StripTags'),
            'decorators' => array('ViewHelper'),
            'placeholder' => 'Surface max. (en m²)'
        ));

        $radio = new Zend_Form_Element_Radio('is_furnished');
        $radio->setSeparator('')
            ->setLabel('Mobilier')
            ->addMultiOptions(array(
                1 => 'Meublé',
                0 => 'Vide'
            ))
            ->setDecorators(array(
                array('MrButtons'),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-white')),
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
            ));
        $this->addElement($radio);

        $chbox = new Zend_Form_Element_MultiCheckbox('number_of_rooms1');
        $chbox->setSeparator('')
            ->setLabel('Nombre de chambres')
            ->addMultiOptions(array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                '>=5' => '5 et +'
            ))
            ->setDecorators(array(
                array('MchBox'),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-white')),
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
            ));
        $this->addElement($chbox);

        $chbox = new Zend_Form_Element_MultiCheckbox('property_type');
        $chbox->setSeparator('')
            ->setLabel('Type de bien')
            ->addMultiOptions(Model_Property::getTypes())
            ->setAttrib('class', 'input-pretty')
            ->setDecorators(array(
                array('MchBox'),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-black')),
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
            ));
        $this->addElement($chbox);

        $chbox = new Zend_Form_Element_MultiCheckbox('number_of_rooms2');
        $chbox->setSeparator('')
            ->setLabel('Nombre de pièces')
            ->addMultiOptions(array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                '>=5' => '5 et +'
            ))
            ->setDecorators(array('ViewHelper'));
        $this->addElement($chbox);

        $radio = new Zend_Form_Element_Radio('availability_select');
        $radio->setSeparator('')
            ->setLabel('Disponibilité')
            ->addMultiOptions(array('now' => 'immediately', 'date' => 'date'))
            ->setDecorators(array('ViewHelper'));
        $this->addElement($radio);

        $this->addElement('datePicker', 'availability', array(
            'JQueryParams' => array (
                'dateFormat' => $settings->get('dateFormat.picker.jquery'),
            ),
        ));
        //$this->addDisplayGroup(array('availability_select', 'availability'), 'availability_group', array('legend' => "availability"));

        //mDump(Model_Property::getPlanningOptions());
        foreach (Model_Property::getPlanningOptions() as $name => $label) {
            $this->addElement('checkbox', $name, array(
                'label'         => $label,
            ));
        }
        $this->addDisplayGroup(array_keys(Model_Property::getPlanningOptions()), 'planning', array('legend' => "planning"));
        
        foreach (Model_Property::getOutbuildingOptions() as $name => $label) {
            $this->addElement('checkbox', $name, array(
                'label'         => $label,
            ));
        }
        $this->addDisplayGroup(array_keys(Model_Property::getOutbuildingOptions()), 'outbuilding', array('legend' => "outbuilding"));
        
        foreach (Model_Property::getExteriorOptions() as $name => $label) {
            $this->addElement('checkbox', $name, array(
                'label'         => $label,
            ));
        }
        $this->addDisplayGroup(array_keys(Model_Property::getExteriorOptions()), 'exterior', array('legend' => "exterior"));
        
        foreach (Model_Property::getBuildingFeatureOptions() as $name => $label) {
            $this->addElement('checkbox', $name, array(
                'label'         => $label,
            ));
        }
        $this->addDisplayGroup(array_keys(Model_Property::getBuildingFeatureOptions()), 'building', array('legend' => "building_features"));
        
        foreach (Model_Property::getHeatingSystemOptions() as $name => $label) {
            $this->addElement('checkbox', $name, array(
                'label'         => $label,
            ));
        }
        $this->addDisplayGroup(array_keys(Model_Property::getHeatingSystemOptions()), 'heating', array('legend' => "heating"));
        
        $this->addElement('submit', 'search', array(
            'label'    => 'search',
        ));
	}

}

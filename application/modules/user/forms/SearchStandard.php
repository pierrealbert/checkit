<?php

class User_Form_SearchStandard extends Ext_Form
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
        $this->setAttrib('id', 'form-search-standard');
        
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        
        $regionBlockOptions = array();
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
        ));

		$this->addElement('text', 'max_budget', array(
            'label'      => 'max_budget',
            'filters'    => array('StringTrim', 'StripTags'),
        ));

		$this->addElement('text', 'min_size', array(
            'label'      => 'min_size',
            'filters'    => array('StringTrim', 'StripTags'),
        ));

		$this->addElement('text', 'max_size', array(
            'label'      => 'max_size',
            'filters'    => array('StringTrim', 'StripTags'),
        ));
        
        $this->addElement('radio', 'is_furnished', array(
            'label'         => 'furniture',
            'multiOptions'  => array('' => 'all',
                                     1 => 'Furnished',
                                     0 => 'Empty'),
        ));
        
        $this->addElement('radio', 'number_of_rooms1', array(
            'label'         => 'number_of_rooms1',
            'multiOptions'  => array('' => 'all',
                                     1 => 1,
                                     2 => 2,
                                     3 => 3,
                                     4 => 4,
                                     '>=5' => '> 5',),
        ));

        $this->addElement('radio', 'property_type', array(
            'label'         => 'property_type',
            'multiOptions'  => array('' => 'all') + Model_Property::getTypes(),
        ));
        
        $this->addElement('radio', 'number_of_rooms2', array(
            'label'         => 'number_of_rooms2',
            'multiOptions'  => array('' => 'all',
                                     1 => 1,
                                     2 => 2,
                                     3 => 3,
                                     4 => 4,
                                     5 => 5,
                                     '>=6' => '> 6',),
        ));
        
        $this->addElement('radio', 'availability_select', array(
            'label'         => 'availability',
            'multiOptions'  => array('' => 'all', 'now' => 'immediately', 'date' => 'date'),
        ));
        
        $this->addElement('datePicker', 'availability', array(
            'JQueryParams' => array (
                'dateFormat' => $settings->get('dateFormat.picker.jquery'),
            ),
        ));
        $this->addDisplayGroup(array('availability_select', 'availability'), 'availability_group', array('legend' => "availability"));
        
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

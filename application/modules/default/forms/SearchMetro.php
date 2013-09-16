<?php

class Form_SearchMetro extends Ext_Form
{
    protected function _addJS() 
    {
        // $br = "\n";
        // $this->getView()->jQuery()->addOnload(
        //     'initSearchStandard();' . $br
        // );
    }
    
    public function init()
    {
        $this->_addJS();
        $this->setMethod('post');
        $this->setAttrib('id', 'form-search-metro');
        
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $this->addElement('hidden', 'metro_station_id', array());
        $this->addElement('hidden', 'metro_line_id', array());
        
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
        
        $this->addElement('radio', 'distance', array(
            'label'         => 'distance',
            'required'      => true,
            'multiOptions'  => array('0.35' => '350 meters',
                                     '0.5' => '500 meters',
                                     '0.65' => '650 meters'),
        ));
	}

}

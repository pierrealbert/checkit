<?php

class Form_SearchMain extends Ext_Form
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
        $this->setAttrib('id', 'form-search-main');
        $this->setAction($this->getView()->url(array(
            'controller' => 'search',
            'action'     => 'main'
        ), null, true));

        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $this->addElement('hidden', 'region_block_id', array('decorators' => array('ViewHelper')));

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

	}

}

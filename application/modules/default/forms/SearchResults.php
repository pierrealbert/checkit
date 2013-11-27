<?php

class Form_SearchResults extends Ext_Form
{
    protected function _addJS()
    {
        /*
        $br = "\n";
        $this->getView()->jQuery()->addOnload(
            'initSearchStandard();' . $br
        );
        */
    }
    
    public function init()
    {
        $this->_addJS();
        $this->setMethod('post');
        $this->setAction($this->getView()->url(array(
            'controller' => 'search',
            'action'     => 'updatesearch'
        ), null, true));
        
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $this->addElement('hidden', 'region_block_id', array('decorators' => array('ViewHelper')));

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
                array('MrButtons', array('labelClass' => 'btn-input-daker')),
            ))->setValue(-1);
        $this->addElement($radio);

        $chbox = new Zend_Form_Element_MultiCheckbox('number_of_rooms1');
        $chbox->setSeparator('')
            ->setLabel('Nombre de chambres')
            ->addMultiOptions(array(
                1 => 1,
                2 => 2,
                3 => 3,
                '>=4' => '4 et +'
            ))
            ->setDecorators(array(
                array('MchBox', array('labelClass' => 'btn-input-daker', 'needAll' => false)),
            ));
        $this->addElement($chbox);

        $chbox = new Zend_Form_Element_MultiCheckbox('property_type');
        $chbox->setSeparator('')
            ->setLabel('Type de bien')
            ->addMultiOptions(Model_Property::getTypes())
            ->setAttrib('class', 'input-pretty')
            ->setDecorators(array(
                array('MchBox', array('labelClass' => 'btn-input-daker')),
            ));
        $this->addElement($chbox);

        $radio = new Zend_Form_Element_Radio('availability_select');
        $radio->setSeparator('')
            ->setLabel('Disponibilité')
            ->addMultiOptions(array('now' => 'Immédiatement'))
            ->setDecorators(array(
                array('MrButtons', array('labelClass' => 'btn-input-daker', 'needAll' => false)),
            ));
        $this->addElement($radio);

        $this->addElement('datePicker', 'availability', array(
            'label' => 'ou à partir de',
            'JQueryParams' => array (
                'dateFormat' => $settings->get('dateFormat.picker.jquery'),
                'nextText' => '',
                'prevText' => ''
            )
        ));

        $radio = new Zend_Form_Element_Radio('rent_period');
        $radio->setSeparator('')
            ->setLabel('Durée du bail')
            ->addMultiOptions(array('short' => 'Courte durée', 'long' => 'Longue durée'))
            ->setDecorators(array(
                array('MrButtons', array('labelClass' => 'btn-input-daker')),
            ));
        $this->addElement($radio);

        $chbox = new Zend_Form_Element_MultiCheckbox('is_roomate');
        $chbox->setSeparator('')
            ->setLabel('Colocation acceptée')
            ->addMultiOptions(array(1 => 'Oui'))
            ->setDecorators(array(
                array('MchBox', array('labelClass' => 'btn-input-daker')),
            ));
        $this->addElement($chbox);

        $chbox = new Zend_Form_Element_MultiCheckbox('planning');
        $chbox->setSeparator('')
            ->setLabel('Aménagement')
            ->addMultiOptions(Model_Property::getPlanningOptions())
            ->setDecorators(array(
                array('MchBox', array('labelClass' => 'btn-input-daker', 'needAll' => false)),
            ));
        $this->addElement($chbox);

        $chbox = new Zend_Form_Element_MultiCheckbox('outbuilding');
        $chbox->setSeparator('')
            ->setLabel('Dépendances')
            ->addMultiOptions(Model_Property::getOutbuildingOptions())
            ->setDecorators(array(
                array('MchBox', array('labelClass' => 'btn-input-daker', 'needAll' => false)),
            ));
        $this->addElement($chbox);

        $chbox = new Zend_Form_Element_MultiCheckbox('exterior');
        $chbox->setSeparator('')
            ->setLabel('Espaces extérieurs')
            ->addMultiOptions(Model_Property::getExteriorOptions())
            ->setDecorators(array(
                array('MchBox', array('labelClass' => 'btn-input-daker', 'needAll' => false)),
            ));
        $this->addElement($chbox);

        $chbox = new Zend_Form_Element_MultiCheckbox('building');
        $chbox->setSeparator('')
            ->setLabel('Immeuble')
            ->addMultiOptions(Model_Property::getBuildingFeatureOptions())
            ->setDecorators(array(
                array('MchBox', array('labelClass' => 'btn-input-daker', 'needAll' => false)),
            ));
        $this->addElement($chbox);

        $chbox = new Zend_Form_Element_MultiCheckbox('heating_system');
        $chbox->setSeparator('')
            ->setLabel('Chauffage')
            ->setValue(array('central_heating'))
            ->addMultiOptions(Model_Property::getHeatingSystemOptions())
            ->setDecorators(array(
                array('MchBox', array('labelClass' => 'btn-input-daker', 'needAll' => false)),
            ));
        $this->addElement($chbox);

        $this->addElement('hidden', 'metro_station_id', array('decorators' => array('ViewHelper')));
        $this->addElement('hidden', 'metro_line_id', array('decorators' => array('ViewHelper')));
        $this->addElement('hidden', 'distance', array('decorators' => array('ViewHelper')));

        $this->addElement('submit', 'search', array(
            'label'    => 'search',
        ));
	}

}

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
        $this->setAction($this->getView()->url(array(
            'controller' => 'search',
            'action'     => 'metro'
        ), null, true));

        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $this->addElement('hidden', 'metro_station_id', array('decorators' => array('ViewHelper')));
        $this->addElement('hidden', 'metro_line_id', array('decorators' => array('ViewHelper')));
        
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
            ))->setValue(-1);
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

        $radio = new Zend_Form_Element_Radio('distance');
        $radio->setSeparator('')
            ->setLabel('distance')
            ->setRequired(true)
            ->addMultiOptions(array(
                '<=0.35' => '350 m',
                '<=0.5' => '500 m',
                '<=0.65' => '650 m'
            ))
            ->setDecorators(array(
                array('MrButtons', array('needAll' => False)),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-white')),
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
            ));
        $this->addElement($radio);

        $this->setDefaults(array(
            'is_furnished' => '-1',
            'number_of_rooms1' => '',
            'distance' => '<=0.5',
        ));
	}

}

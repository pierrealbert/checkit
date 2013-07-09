<?php

class Admin_Form_Settings extends Ext_Form_UiTabForm
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('text', 'groupName_option1', array(
            'label'         => 'some_option_1',
            'required'      => true,
            'filters'       => array('StringTrim')
        ));

        $this->addElement('text', 'groupName_option2', array(
            'label'         => 'some_option_2',
            'required'      => true,
            'filters'       => array('StringTrim')
        ));

        $this->addElement('text', 'groupName_option3', array(
            'label'         => 'some_option_3',
            'required'      => true,
            'filters'       => array('StringTrim')
        ));
                       
        $this->addDisplayGroup(array(
            'groupName_option1',
            'groupName_option2'
        ), 'general');

        $this->addDisplayGroup(array(
            'groupName_option3'
        ), 'general2');

        $this->setTabs(array(
            array(
                'title' => 'general',
                'ref'   => '#fieldset-general',
            ),
            array(
                'title' => 'objectives',
                'ref'   => '#fieldset-general2',
            )
        ));

        $this->addElement('submit', 'save', array(
            'label' => 'save',
            'class' => 'ui-state-default ui-corner-all'
        ));
        
        $this->addDisplayGroupButtons(array(
            'save'
        ));
    }

    public function setSettings(array $settings)
    {
        foreach ($settings as $settingOption) {
            $elementName = str_replace('.', '_', $settingOption['name']);
            $this->setDefault($elementName, $settingOption['value']);
        }
    }

    public function getSettingsValues()
    {
        $values = $this->getValues();

        $settings = array();
        foreach ($values as $key => $value) {
            $nameSegments = explode('_', $key);

            $name = array_shift($nameSegments);
            $name = $name . '.' . implode('_', $nameSegments);

            $settings[$name] = $value;
        }

        return $settings;
    }
}
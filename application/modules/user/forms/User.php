<?php

class User_Form_User extends Ext_Form
{
    public function init()
    {
        $this->setAttrib('id', 'form-product');

        $this->addElement('text', 'email', array(
            'label'      => 'email',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array(
                    'entityNotExists',
                    true,
                    array('Model_User', 'email')
                )
            )
        ));

        $this->addElement('text', 'first_name', array(
            'label'      => 'first_name',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));

        $this->addElement('text', 'last_name', array(
            'label'      => 'last_name',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));

        $countries = array(
            '' => $this->getTranslator()->translate('please_select')
        );
        $countries = $countries + Zend_Locale::getTranslationList('territory', null, 2);

        $this->addElement('select', 'country', array(
            'label'         => 'country',
            'required'      => true,
            'multiOptions'  => $countries

        ));

        $this->addElement('text', 'city', array(
            'label'         => 'city',
            'filters'       => array('StringTrim')
        ));

        $this->addElement('submit', 'save', array(
            'label'     => 'update',
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }

    public function setDefaults(array $defaults)
    {
        if (!empty($defaults['id'])) {
            $this->getElement('email')
                    ->getValidator('entityNotExists')
                    ->addExclusion('id', $defaults['id']);
        }

        return parent::setDefaults($defaults);
    }
}

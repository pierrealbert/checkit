<?php

class Admin_Form_User extends Ext_Form
{
    public function init()
    {
        $this->setAttrib('id', 'form-product');

        $this->addElement('text', 'username', array(
            'label'      => 'username',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(
                    'min' => 3
                )),                
                array(
                    'entityNotExists',
                    true,
                    array('Model_User', 'username')
                )
            )
        ));

        $this->addElement('text', 'email', array(
            'label'      => 'email',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
                array(
                    'entityNotExists',
                    true,
                    array('Model_User', 'email')
                )
            )
        ));

        $this->addElement('password', 'password', array(
            'label'         => 'password',
            'filters'       => array('StringTrim'),
            'autocomplete'  => 'off',
             'required'     => true,
            'validators'    => array(
                array('StringLength', false, array(
                    'min' => 3
                ))
            )            
        ));

        $this->addElement('password', 'confirm_password', array(
            'label'         => 'confirm_password',
            'filters'       => array('StringTrim'),
            'autocomplete'  => 'off',
             'required'     => true,
            'validators'    => array (
                new Zend_Validate_Identical('password')
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
            'label'     => 'save',
            'class'     => 'ui-state-default ui-corner-all'
        ));
        $this->addElement('submit', 'cancel', array(
            'label'     => 'cancel',
            'class'     => 'ui-state-default ui-corner-all'
        ));

        $this->addElement('checkbox', 'is_active', array(
            'label' => 'is_active',
            'value' => 1
        ));

        $this->addElement('checkbox', 'is_confirmed', array(
            'label' => 'is_confirmed'
        ));
        
        $this->addDisplayGroupButtons(array('save', 'cancel'));
    }

    public function setDefaults($defaults)
    {
        if (!empty($defaults['id'])) {
            $this->getElement('username')
                    ->getValidator('entityNotExists')
                    ->addExclusion('id', $defaults['id']);

            $this->getElement('email')
                    ->getValidator('entityNotExists')
                    ->addExclusion('id', $defaults['id']);
            
            $this->getElement('password')->setRequired(false);
            $this->getElement('confirm_password')->setRequired(false);
        }

        return parent::setDefaults($defaults);
    }

    public function isValid($data)
    {
        if (!empty($data['password']) || !empty($data['confirm_password'])) {
            $this->getElement('password')->setRequired();
            $this->getElement('confirm_password')->setRequired();
        }
        return parent::isValid($data);
    }
}
